import http from 'k6/http';
import { check } from 'k6';

// Run with:
// k6 run -e SEAT_ID=<uuid> -e BASE_URL=http://localhost load-tests/seat-contention.js
const SEAT_ID = __ENV.SEAT_ID;
const BASE_URL = __ENV.BASE_URL || 'http://localhost';

export const options = {
    vus: 20,
    iterations: 20,
};

function getXsrfToken(jar, url) {
    const cookies = jar.cookiesForURL(url);
    const raw = cookies['XSRF-TOKEN'] ? cookies['XSRF-TOKEN'][0] : null;
    return raw ? decodeURIComponent(raw) : null;
}

export default function () {
    const vuId = __VU;
    const jar = http.cookieJar();

    // Step 1: hit any page to receive session + XSRF-TOKEN cookies
    http.get(`${BASE_URL}/login`);
    let xsrfToken = getXsrfToken(jar, BASE_URL);

    // Step 2: log in the way Inertia/axios actually does it —
    // JSON body + X-XSRF-TOKEN header, not a scraped form field
    const loginRes = http.post(
        `${BASE_URL}/login`,
        JSON.stringify({
            email: `user${vuId}@test.com`,
            password: 'password',
        }),
        {
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-XSRF-TOKEN': xsrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
        },
    );

    // DEBUG: uncomment while getting this working, remove once it passes
    // console.log(`VU ${vuId} login status: ${loginRes.status} body: ${loginRes.body}`);

    check(loginRes, {
        'login succeeded': (r) =>
            r.status === 200 || r.status === 204 || r.status === 302,
    });

    // Laravel rotates the session (and CSRF token) after login — re-read it
    xsrfToken = getXsrfToken(jar, BASE_URL);

    const lockRes = http.post(`${BASE_URL}/seats/${SEAT_ID}/lock`, null, {
        headers: {
            Accept: 'application/json',
            'X-XSRF-TOKEN': xsrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    // console.log(`VU ${vuId} lock status: ${lockRes.status} body: ${lockRes.body}`);

    check(lockRes, {
        'seat was locked (200) or correctly rejected (409)': (r) =>
            r.status === 200 || r.status === 409,
    });
}
