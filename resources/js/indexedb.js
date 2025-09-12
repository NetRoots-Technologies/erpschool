'use strict';

let db;
const request = indexedDB.open("MyTestDatabase");
request.onerror = (event) => {
    console.error("Why didn't you allow my web app to use IndexedDB?!");
};
request.onsuccess = (event) => {
    db = event.target.result;
};

db.onerror = (event) => {
    console.error(`Database error: ${event.target.error?.message}`);
};
