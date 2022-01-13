export function doGetter(store) {
    return key => store[key] || key;
}

export function arrayAsMap(array, keyBuilder) {
    if (!Array.isArray(array)) return  array;
    return array.reduce((acc, item) => {
        acc[keyBuilder(item)] = item;
        return acc;
    }, {});
}

export function doDefault(source, target, keyBuilder, valueBuilder) {

    if (Array.isArray(target)) {
        const map = arrayAsMap(target, keyBuilder)
        source.forEach(s => {
            const key = keyBuilder(s);
            if (!map[key]) {
                target.push(valueBuilder(s));
            }
        });
    }else{
        source.forEach(s => {
            const key = keyBuilder(s);
            if (!target[key]) {
                target[key]=valueBuilder(s);
            }
        });
    }
}