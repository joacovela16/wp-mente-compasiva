let lastPage = 1;
let lastQuery = "";

export function setLastPage(p) {
    lastPage = p;
}
export function setLastQuery(q){
    lastQuery =q;
}

export function getLastQuery() {
    return lastQuery;
}
export function getLastPage() {
    return lastPage;
}