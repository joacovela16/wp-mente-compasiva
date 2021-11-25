
function Writer(initial) {
    let value = initial;
    let subscribers = [];

    return {
        update(func) {
            if (func) {
                value = func(value);
                subscribers.forEach(x => x(value));
            }
        },
        subscribe(func) {
            if (func) subscribers.push(func);
        }
    };
}

const LoaderState = new Writer(0);

export default LoaderState;

export function isWaiting() {
    LoaderState.update(x => x + 1);
}

export function isReady() {
    LoaderState.update(x => x - 1);
}

export function waitFor(data) {
    if (data) {
        isWaiting();
        data.finally(() => {
            isReady();
        });
        return data;
    }
}
