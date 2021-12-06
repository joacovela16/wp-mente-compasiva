export function isDefined(obj) {
    return !isUndefined(obj);
}

export function isUndefined(obj) {
    return obj === undefined || obj === null;
}

export function nonEmpty(str) {
    return !isEmpty(str);
}

export function isEmpty(str) {
    return isUndefined(str) || str.trim().length === 0;
}