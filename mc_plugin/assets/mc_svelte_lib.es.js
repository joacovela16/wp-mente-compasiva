var __defProp = Object.defineProperty;
var __defProps = Object.defineProperties;
var __getOwnPropDescs = Object.getOwnPropertyDescriptors;
var __getOwnPropSymbols = Object.getOwnPropertySymbols;
var __hasOwnProp = Object.prototype.hasOwnProperty;
var __propIsEnum = Object.prototype.propertyIsEnumerable;
var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: true, configurable: true, writable: true, value }) : obj[key] = value;
var __spreadValues = (a, b) => {
  for (var prop in b || (b = {}))
    if (__hasOwnProp.call(b, prop))
      __defNormalProp(a, prop, b[prop]);
  if (__getOwnPropSymbols)
    for (var prop of __getOwnPropSymbols(b)) {
      if (__propIsEnum.call(b, prop))
        __defNormalProp(a, prop, b[prop]);
    }
  return a;
};
var __spreadProps = (a, b) => __defProps(a, __getOwnPropDescs(b));
var windi = "";
function noop() {
}
const identity = (x) => x;
function assign(tar, src) {
  for (const k in src)
    tar[k] = src[k];
  return tar;
}
function run(fn) {
  return fn();
}
function blank_object() {
  return /* @__PURE__ */ Object.create(null);
}
function run_all(fns) {
  fns.forEach(run);
}
function is_function(thing) {
  return typeof thing === "function";
}
function safe_not_equal(a, b) {
  return a != a ? b == b : a !== b || (a && typeof a === "object" || typeof a === "function");
}
let src_url_equal_anchor;
function src_url_equal(element_src, url) {
  if (!src_url_equal_anchor) {
    src_url_equal_anchor = document.createElement("a");
  }
  src_url_equal_anchor.href = url;
  return element_src === src_url_equal_anchor.href;
}
function is_empty(obj) {
  return Object.keys(obj).length === 0;
}
function create_slot(definition, ctx, $$scope, fn) {
  if (definition) {
    const slot_ctx = get_slot_context(definition, ctx, $$scope, fn);
    return definition[0](slot_ctx);
  }
}
function get_slot_context(definition, ctx, $$scope, fn) {
  return definition[1] && fn ? assign($$scope.ctx.slice(), definition[1](fn(ctx))) : $$scope.ctx;
}
function get_slot_changes(definition, $$scope, dirty, fn) {
  if (definition[2] && fn) {
    const lets = definition[2](fn(dirty));
    if ($$scope.dirty === void 0) {
      return lets;
    }
    if (typeof lets === "object") {
      const merged = [];
      const len = Math.max($$scope.dirty.length, lets.length);
      for (let i = 0; i < len; i += 1) {
        merged[i] = $$scope.dirty[i] | lets[i];
      }
      return merged;
    }
    return $$scope.dirty | lets;
  }
  return $$scope.dirty;
}
function update_slot_base(slot, slot_definition, ctx, $$scope, slot_changes, get_slot_context_fn) {
  if (slot_changes) {
    const slot_context = get_slot_context(slot_definition, ctx, $$scope, get_slot_context_fn);
    slot.p(slot_context, slot_changes);
  }
}
function get_all_dirty_from_scope($$scope) {
  if ($$scope.ctx.length > 32) {
    const dirty = [];
    const length = $$scope.ctx.length / 32;
    for (let i = 0; i < length; i++) {
      dirty[i] = -1;
    }
    return dirty;
  }
  return -1;
}
const is_client = typeof window !== "undefined";
let now = is_client ? () => window.performance.now() : () => Date.now();
let raf = is_client ? (cb) => requestAnimationFrame(cb) : noop;
const tasks = /* @__PURE__ */ new Set();
function run_tasks(now2) {
  tasks.forEach((task) => {
    if (!task.c(now2)) {
      tasks.delete(task);
      task.f();
    }
  });
  if (tasks.size !== 0)
    raf(run_tasks);
}
function loop(callback) {
  let task;
  if (tasks.size === 0)
    raf(run_tasks);
  return {
    promise: new Promise((fulfill) => {
      tasks.add(task = { c: callback, f: fulfill });
    }),
    abort() {
      tasks.delete(task);
    }
  };
}
function append(target, node) {
  target.appendChild(node);
}
function get_root_for_style(node) {
  if (!node)
    return document;
  const root = node.getRootNode ? node.getRootNode() : node.ownerDocument;
  if (root && root.host) {
    return root;
  }
  return node.ownerDocument;
}
function append_empty_stylesheet(node) {
  const style_element = element("style");
  append_stylesheet(get_root_for_style(node), style_element);
  return style_element.sheet;
}
function append_stylesheet(node, style) {
  append(node.head || node, style);
}
function insert(target, node, anchor) {
  target.insertBefore(node, anchor || null);
}
function detach(node) {
  node.parentNode.removeChild(node);
}
function destroy_each(iterations, detaching) {
  for (let i = 0; i < iterations.length; i += 1) {
    if (iterations[i])
      iterations[i].d(detaching);
  }
}
function element(name) {
  return document.createElement(name);
}
function svg_element(name) {
  return document.createElementNS("http://www.w3.org/2000/svg", name);
}
function text(data) {
  return document.createTextNode(data);
}
function space() {
  return text(" ");
}
function empty() {
  return text("");
}
function listen(node, event, handler, options) {
  node.addEventListener(event, handler, options);
  return () => node.removeEventListener(event, handler, options);
}
function prevent_default(fn) {
  return function(event) {
    event.preventDefault();
    return fn.call(this, event);
  };
}
function attr(node, attribute, value) {
  if (value == null)
    node.removeAttribute(attribute);
  else if (node.getAttribute(attribute) !== value)
    node.setAttribute(attribute, value);
}
function get_binding_group_value(group, __value, checked) {
  const value = /* @__PURE__ */ new Set();
  for (let i = 0; i < group.length; i += 1) {
    if (group[i].checked)
      value.add(group[i].__value);
  }
  if (!checked) {
    value.delete(__value);
  }
  return Array.from(value);
}
function children(element2) {
  return Array.from(element2.childNodes);
}
function set_data(text2, data) {
  data = "" + data;
  if (text2.wholeText !== data)
    text2.data = data;
}
function set_input_value(input, value) {
  input.value = value == null ? "" : value;
}
function toggle_class(element2, name, toggle) {
  element2.classList[toggle ? "add" : "remove"](name);
}
function custom_event(type, detail, bubbles = false) {
  const e = document.createEvent("CustomEvent");
  e.initCustomEvent(type, bubbles, false, detail);
  return e;
}
const managed_styles = /* @__PURE__ */ new Map();
let active = 0;
function hash(str) {
  let hash2 = 5381;
  let i = str.length;
  while (i--)
    hash2 = (hash2 << 5) - hash2 ^ str.charCodeAt(i);
  return hash2 >>> 0;
}
function create_style_information(doc, node) {
  const info = { stylesheet: append_empty_stylesheet(node), rules: {} };
  managed_styles.set(doc, info);
  return info;
}
function create_rule(node, a, b, duration, delay, ease, fn, uid = 0) {
  const step = 16.666 / duration;
  let keyframes = "{\n";
  for (let p = 0; p <= 1; p += step) {
    const t = a + (b - a) * ease(p);
    keyframes += p * 100 + `%{${fn(t, 1 - t)}}
`;
  }
  const rule = keyframes + `100% {${fn(b, 1 - b)}}
}`;
  const name = `__svelte_${hash(rule)}_${uid}`;
  const doc = get_root_for_style(node);
  const { stylesheet, rules } = managed_styles.get(doc) || create_style_information(doc, node);
  if (!rules[name]) {
    rules[name] = true;
    stylesheet.insertRule(`@keyframes ${name} ${rule}`, stylesheet.cssRules.length);
  }
  const animation = node.style.animation || "";
  node.style.animation = `${animation ? `${animation}, ` : ""}${name} ${duration}ms linear ${delay}ms 1 both`;
  active += 1;
  return name;
}
function delete_rule(node, name) {
  const previous = (node.style.animation || "").split(", ");
  const next = previous.filter(name ? (anim) => anim.indexOf(name) < 0 : (anim) => anim.indexOf("__svelte") === -1);
  const deleted = previous.length - next.length;
  if (deleted) {
    node.style.animation = next.join(", ");
    active -= deleted;
    if (!active)
      clear_rules();
  }
}
function clear_rules() {
  raf(() => {
    if (active)
      return;
    managed_styles.forEach((info) => {
      const { stylesheet } = info;
      let i = stylesheet.cssRules.length;
      while (i--)
        stylesheet.deleteRule(i);
      info.rules = {};
    });
    managed_styles.clear();
  });
}
let current_component;
function set_current_component(component) {
  current_component = component;
}
const dirty_components = [];
const binding_callbacks = [];
const render_callbacks = [];
const flush_callbacks = [];
const resolved_promise = Promise.resolve();
let update_scheduled = false;
function schedule_update() {
  if (!update_scheduled) {
    update_scheduled = true;
    resolved_promise.then(flush);
  }
}
function add_render_callback(fn) {
  render_callbacks.push(fn);
}
const seen_callbacks = /* @__PURE__ */ new Set();
let flushidx = 0;
function flush() {
  const saved_component = current_component;
  do {
    while (flushidx < dirty_components.length) {
      const component = dirty_components[flushidx];
      flushidx++;
      set_current_component(component);
      update(component.$$);
    }
    set_current_component(null);
    dirty_components.length = 0;
    flushidx = 0;
    while (binding_callbacks.length)
      binding_callbacks.pop()();
    for (let i = 0; i < render_callbacks.length; i += 1) {
      const callback = render_callbacks[i];
      if (!seen_callbacks.has(callback)) {
        seen_callbacks.add(callback);
        callback();
      }
    }
    render_callbacks.length = 0;
  } while (dirty_components.length);
  while (flush_callbacks.length) {
    flush_callbacks.pop()();
  }
  update_scheduled = false;
  seen_callbacks.clear();
  set_current_component(saved_component);
}
function update($$) {
  if ($$.fragment !== null) {
    $$.update();
    run_all($$.before_update);
    const dirty = $$.dirty;
    $$.dirty = [-1];
    $$.fragment && $$.fragment.p($$.ctx, dirty);
    $$.after_update.forEach(add_render_callback);
  }
}
let promise;
function wait() {
  if (!promise) {
    promise = Promise.resolve();
    promise.then(() => {
      promise = null;
    });
  }
  return promise;
}
function dispatch(node, direction, kind) {
  node.dispatchEvent(custom_event(`${direction ? "intro" : "outro"}${kind}`));
}
const outroing = /* @__PURE__ */ new Set();
let outros;
function group_outros() {
  outros = {
    r: 0,
    c: [],
    p: outros
  };
}
function check_outros() {
  if (!outros.r) {
    run_all(outros.c);
  }
  outros = outros.p;
}
function transition_in(block, local) {
  if (block && block.i) {
    outroing.delete(block);
    block.i(local);
  }
}
function transition_out(block, local, detach2, callback) {
  if (block && block.o) {
    if (outroing.has(block))
      return;
    outroing.add(block);
    outros.c.push(() => {
      outroing.delete(block);
      if (callback) {
        if (detach2)
          block.d(1);
        callback();
      }
    });
    block.o(local);
  }
}
const null_transition = { duration: 0 };
function create_in_transition(node, fn, params) {
  let config = fn(node, params);
  let running = false;
  let animation_name;
  let task;
  let uid = 0;
  function cleanup() {
    if (animation_name)
      delete_rule(node, animation_name);
  }
  function go() {
    const { delay = 0, duration = 300, easing = identity, tick = noop, css } = config || null_transition;
    if (css)
      animation_name = create_rule(node, 0, 1, duration, delay, easing, css, uid++);
    tick(0, 1);
    const start_time = now() + delay;
    const end_time = start_time + duration;
    if (task)
      task.abort();
    running = true;
    add_render_callback(() => dispatch(node, true, "start"));
    task = loop((now2) => {
      if (running) {
        if (now2 >= end_time) {
          tick(1, 0);
          dispatch(node, true, "end");
          cleanup();
          return running = false;
        }
        if (now2 >= start_time) {
          const t = easing((now2 - start_time) / duration);
          tick(t, 1 - t);
        }
      }
      return running;
    });
  }
  let started = false;
  return {
    start() {
      if (started)
        return;
      started = true;
      delete_rule(node);
      if (is_function(config)) {
        config = config();
        wait().then(go);
      } else {
        go();
      }
    },
    invalidate() {
      started = false;
    },
    end() {
      if (running) {
        cleanup();
        running = false;
      }
    }
  };
}
function destroy_block(block, lookup) {
  block.d(1);
  lookup.delete(block.key);
}
function update_keyed_each(old_blocks, dirty, get_key, dynamic, ctx, list, lookup, node, destroy, create_each_block2, next, get_context) {
  let o = old_blocks.length;
  let n = list.length;
  let i = o;
  const old_indexes = {};
  while (i--)
    old_indexes[old_blocks[i].key] = i;
  const new_blocks = [];
  const new_lookup = /* @__PURE__ */ new Map();
  const deltas = /* @__PURE__ */ new Map();
  i = n;
  while (i--) {
    const child_ctx = get_context(ctx, list, i);
    const key = get_key(child_ctx);
    let block = lookup.get(key);
    if (!block) {
      block = create_each_block2(key, child_ctx);
      block.c();
    } else if (dynamic) {
      block.p(child_ctx, dirty);
    }
    new_lookup.set(key, new_blocks[i] = block);
    if (key in old_indexes)
      deltas.set(key, Math.abs(i - old_indexes[key]));
  }
  const will_move = /* @__PURE__ */ new Set();
  const did_move = /* @__PURE__ */ new Set();
  function insert2(block) {
    transition_in(block, 1);
    block.m(node, next);
    lookup.set(block.key, block);
    next = block.first;
    n--;
  }
  while (o && n) {
    const new_block = new_blocks[n - 1];
    const old_block = old_blocks[o - 1];
    const new_key = new_block.key;
    const old_key = old_block.key;
    if (new_block === old_block) {
      next = new_block.first;
      o--;
      n--;
    } else if (!new_lookup.has(old_key)) {
      destroy(old_block, lookup);
      o--;
    } else if (!lookup.has(new_key) || will_move.has(new_key)) {
      insert2(new_block);
    } else if (did_move.has(old_key)) {
      o--;
    } else if (deltas.get(new_key) > deltas.get(old_key)) {
      did_move.add(new_key);
      insert2(new_block);
    } else {
      will_move.add(old_key);
      o--;
    }
  }
  while (o--) {
    const old_block = old_blocks[o];
    if (!new_lookup.has(old_block.key))
      destroy(old_block, lookup);
  }
  while (n)
    insert2(new_blocks[n - 1]);
  return new_blocks;
}
function create_component(block) {
  block && block.c();
}
function mount_component(component, target, anchor, customElement) {
  const { fragment, on_mount, on_destroy, after_update } = component.$$;
  fragment && fragment.m(target, anchor);
  if (!customElement) {
    add_render_callback(() => {
      const new_on_destroy = on_mount.map(run).filter(is_function);
      if (on_destroy) {
        on_destroy.push(...new_on_destroy);
      } else {
        run_all(new_on_destroy);
      }
      component.$$.on_mount = [];
    });
  }
  after_update.forEach(add_render_callback);
}
function destroy_component(component, detaching) {
  const $$ = component.$$;
  if ($$.fragment !== null) {
    run_all($$.on_destroy);
    $$.fragment && $$.fragment.d(detaching);
    $$.on_destroy = $$.fragment = null;
    $$.ctx = [];
  }
}
function make_dirty(component, i) {
  if (component.$$.dirty[0] === -1) {
    dirty_components.push(component);
    schedule_update();
    component.$$.dirty.fill(0);
  }
  component.$$.dirty[i / 31 | 0] |= 1 << i % 31;
}
function init(component, options, instance2, create_fragment2, not_equal, props, append_styles, dirty = [-1]) {
  const parent_component = current_component;
  set_current_component(component);
  const $$ = component.$$ = {
    fragment: null,
    ctx: null,
    props,
    update: noop,
    not_equal,
    bound: blank_object(),
    on_mount: [],
    on_destroy: [],
    on_disconnect: [],
    before_update: [],
    after_update: [],
    context: new Map(options.context || (parent_component ? parent_component.$$.context : [])),
    callbacks: blank_object(),
    dirty,
    skip_bound: false,
    root: options.target || parent_component.$$.root
  };
  append_styles && append_styles($$.root);
  let ready = false;
  $$.ctx = instance2 ? instance2(component, options.props || {}, (i, ret, ...rest) => {
    const value = rest.length ? rest[0] : ret;
    if ($$.ctx && not_equal($$.ctx[i], $$.ctx[i] = value)) {
      if (!$$.skip_bound && $$.bound[i])
        $$.bound[i](value);
      if (ready)
        make_dirty(component, i);
    }
    return ret;
  }) : [];
  $$.update();
  ready = true;
  run_all($$.before_update);
  $$.fragment = create_fragment2 ? create_fragment2($$.ctx) : false;
  if (options.target) {
    if (options.hydrate) {
      const nodes = children(options.target);
      $$.fragment && $$.fragment.l(nodes);
      nodes.forEach(detach);
    } else {
      $$.fragment && $$.fragment.c();
    }
    if (options.intro)
      transition_in(component.$$.fragment);
    mount_component(component, options.target, options.anchor, options.customElement);
    flush();
  }
  set_current_component(parent_component);
}
class SvelteComponent {
  $destroy() {
    destroy_component(this, 1);
    this.$destroy = noop;
  }
  $on(type, callback) {
    const callbacks = this.$$.callbacks[type] || (this.$$.callbacks[type] = []);
    callbacks.push(callback);
    return () => {
      const index = callbacks.indexOf(callback);
      if (index !== -1)
        callbacks.splice(index, 1);
    };
  }
  $set($$props) {
    if (this.$$set && !is_empty($$props)) {
      this.$$.skip_bound = true;
      this.$$set($$props);
      this.$$.skip_bound = false;
    }
  }
}
function doGetter(store) {
  return (key) => store[key] || key;
}
function arrayAsMap(array, keyBuilder) {
  if (!Array.isArray(array))
    return array;
  return array.reduce((acc, item) => {
    acc[keyBuilder(item)] = item;
    return acc;
  }, {});
}
function doDefault(source, target, keyBuilder, valueBuilder) {
  if (Array.isArray(target)) {
    const map = arrayAsMap(target, keyBuilder);
    source.forEach((s) => {
      const key = keyBuilder(s);
      if (!map[key]) {
        target.push(valueBuilder(s));
      }
    });
  } else {
    source.forEach((s) => {
      const key = keyBuilder(s);
      if (!target[key]) {
        target[key] = valueBuilder(s);
      }
    });
  }
}
function isUndefined(obj) {
  return obj === void 0 || obj === null;
}
function nonEmpty(str) {
  return !isEmpty(str);
}
function isEmpty(str) {
  return isUndefined(str) || str.trim().length === 0;
}
function get_each_context$2(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[11] = list[i];
  child_ctx[12] = list;
  child_ctx[13] = i;
  return child_ctx;
}
function get_each_context_1$2(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[14] = list[i];
  return child_ctx;
}
function get_each_context_2$2(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[14] = list[i];
  return child_ctx;
}
function get_each_context_3$2(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[11] = list[i];
  child_ctx[13] = i;
  return child_ctx;
}
function create_each_block_3$2(key_1, ctx) {
  let div;
  let t0_value = ctx[11].name + "";
  let t0;
  let t1;
  let div_class_value;
  let mounted;
  let dispose;
  function click_handler() {
    return ctx[6](ctx[13]);
  }
  return {
    key: key_1,
    first: null,
    c() {
      div = element("div");
      t0 = text(t0_value);
      t1 = space();
      attr(div, "class", div_class_value = "shadow font-bold hover:bg-blue-500 hover:text-white cursor-pointer rounded flex-1 p-2 " + (ctx[2].id === ctx[11].id && "bg-blue-500 text-white"));
      this.first = div;
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, t0);
      append(div, t1);
      if (!mounted) {
        dispose = listen(div, "click", click_handler);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (dirty & 4 && div_class_value !== (div_class_value = "shadow font-bold hover:bg-blue-500 hover:text-white cursor-pointer rounded flex-1 p-2 " + (ctx[2].id === ctx[11].id && "bg-blue-500 text-white"))) {
        attr(div, "class", div_class_value);
      }
    },
    d(detaching) {
      if (detaching)
        detach(div);
      mounted = false;
      dispose();
    }
  };
}
function create_each_block_2$2(ctx) {
  let div;
  let label;
  let input;
  let input_name_value;
  let t0;
  let span;
  let t1_value = ctx[14] + "";
  let t1;
  let t2;
  let mounted;
  let dispose;
  ctx[8][1][ctx[13]] = [];
  function input_change_handler() {
    ctx[7].call(input, ctx[11], ctx[13]);
  }
  return {
    c() {
      div = element("div");
      label = element("label");
      input = element("input");
      t0 = space();
      span = element("span");
      t1 = text(t1_value);
      t2 = space();
      attr(input, "type", "checkbox");
      input.__value = ctx[14];
      input.value = input.__value;
      attr(input, "name", input_name_value = ctx[0].names.permission + "[" + ctx[11].id + "][post_types][]");
      ctx[8][1][ctx[13]].push(input);
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, label);
      append(label, input);
      input.checked = ~ctx[1][ctx[11].id].post_types.indexOf(input.__value);
      append(label, t0);
      append(label, span);
      append(span, t1);
      append(div, t2);
      if (!mounted) {
        dispose = listen(input, "change", input_change_handler);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (dirty & 1 && input_name_value !== (input_name_value = ctx[0].names.permission + "[" + ctx[11].id + "][post_types][]")) {
        attr(input, "name", input_name_value);
      }
      if (dirty & 10) {
        input.checked = ~ctx[1][ctx[11].id].post_types.indexOf(input.__value);
      }
    },
    d(detaching) {
      if (detaching)
        detach(div);
      ctx[8][1][ctx[13]].splice(ctx[8][1][ctx[13]].indexOf(input), 1);
      mounted = false;
      dispose();
    }
  };
}
function create_each_block_1$2(ctx) {
  let div;
  let label;
  let input;
  let input_name_value;
  let t0;
  let span;
  let t1_value = ctx[14] + "";
  let t1;
  let t2;
  let mounted;
  let dispose;
  ctx[8][0][ctx[13]] = [];
  function input_change_handler_1() {
    ctx[9].call(input, ctx[11], ctx[13]);
  }
  return {
    c() {
      div = element("div");
      label = element("label");
      input = element("input");
      t0 = space();
      span = element("span");
      t1 = text(t1_value);
      t2 = space();
      attr(input, "type", "checkbox");
      input.__value = ctx[14];
      input.value = input.__value;
      attr(input, "name", input_name_value = ctx[0].names.permission + "[" + ctx[11].id + "][capabilities][]");
      ctx[8][0][ctx[13]].push(input);
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, label);
      append(label, input);
      input.checked = ~ctx[1][ctx[11].id].capabilities.indexOf(input.__value);
      append(label, t0);
      append(label, span);
      append(span, t1);
      append(div, t2);
      if (!mounted) {
        dispose = listen(input, "change", input_change_handler_1);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (dirty & 1 && input_name_value !== (input_name_value = ctx[0].names.permission + "[" + ctx[11].id + "][capabilities][]")) {
        attr(input, "name", input_name_value);
      }
      if (dirty & 10) {
        input.checked = ~ctx[1][ctx[11].id].capabilities.indexOf(input.__value);
      }
    },
    d(detaching) {
      if (detaching)
        detach(div);
      ctx[8][0][ctx[13]].splice(ctx[8][0][ctx[13]].indexOf(input), 1);
      mounted = false;
      dispose();
    }
  };
}
function create_each_block$2(key_1, ctx) {
  let input;
  let input_name_value;
  let t0;
  let div4;
  let div1;
  let div0;
  let t2;
  let t3;
  let div3;
  let div2;
  let t5;
  let t6;
  let each_value_2 = ctx[11].post_types;
  let each_blocks_1 = [];
  for (let i = 0; i < each_value_2.length; i += 1) {
    each_blocks_1[i] = create_each_block_2$2(get_each_context_2$2(ctx, each_value_2, i));
  }
  let each_value_1 = ctx[11].capabilities || [];
  let each_blocks = [];
  for (let i = 0; i < each_value_1.length; i += 1) {
    each_blocks[i] = create_each_block_1$2(get_each_context_1$2(ctx, each_value_1, i));
  }
  return {
    key: key_1,
    first: null,
    c() {
      input = element("input");
      t0 = space();
      div4 = element("div");
      div1 = element("div");
      div0 = element("div");
      div0.textContent = `${ctx[4]("Post types")}`;
      t2 = space();
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].c();
      }
      t3 = space();
      div3 = element("div");
      div2 = element("div");
      div2.textContent = `${ctx[4]("Capabilities")}`;
      t5 = space();
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      t6 = space();
      attr(input, "type", "hidden");
      input.value = ctx[11].id;
      attr(input, "name", input_name_value = ctx[0].names.permission + "[" + ctx[11].id + "][id]");
      attr(div1, "class", "flex-1");
      attr(div3, "class", "flex-1");
      attr(div4, "class", "p-2 flex flex-row");
      toggle_class(div4, "hidden", ctx[11].id !== ctx[2].id);
      this.first = input;
    },
    m(target, anchor) {
      insert(target, input, anchor);
      insert(target, t0, anchor);
      insert(target, div4, anchor);
      append(div4, div1);
      append(div1, div0);
      append(div1, t2);
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].m(div1, null);
      }
      append(div4, t3);
      append(div4, div3);
      append(div3, div2);
      append(div3, t5);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(div3, null);
      }
      append(div4, t6);
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (dirty & 1 && input_name_value !== (input_name_value = ctx[0].names.permission + "[" + ctx[11].id + "][id]")) {
        attr(input, "name", input_name_value);
      }
      if (dirty & 11) {
        each_value_2 = ctx[11].post_types;
        let i;
        for (i = 0; i < each_value_2.length; i += 1) {
          const child_ctx = get_each_context_2$2(ctx, each_value_2, i);
          if (each_blocks_1[i]) {
            each_blocks_1[i].p(child_ctx, dirty);
          } else {
            each_blocks_1[i] = create_each_block_2$2(child_ctx);
            each_blocks_1[i].c();
            each_blocks_1[i].m(div1, null);
          }
        }
        for (; i < each_blocks_1.length; i += 1) {
          each_blocks_1[i].d(1);
        }
        each_blocks_1.length = each_value_2.length;
      }
      if (dirty & 11) {
        each_value_1 = ctx[11].capabilities || [];
        let i;
        for (i = 0; i < each_value_1.length; i += 1) {
          const child_ctx = get_each_context_1$2(ctx, each_value_1, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
          } else {
            each_blocks[i] = create_each_block_1$2(child_ctx);
            each_blocks[i].c();
            each_blocks[i].m(div3, null);
          }
        }
        for (; i < each_blocks.length; i += 1) {
          each_blocks[i].d(1);
        }
        each_blocks.length = each_value_1.length;
      }
      if (dirty & 12) {
        toggle_class(div4, "hidden", ctx[11].id !== ctx[2].id);
      }
    },
    d(detaching) {
      if (detaching)
        detach(input);
      if (detaching)
        detach(t0);
      if (detaching)
        detach(div4);
      destroy_each(each_blocks_1, detaching);
      destroy_each(each_blocks, detaching);
    }
  };
}
function create_fragment$4(ctx) {
  let div1;
  let div0;
  let each_blocks_1 = [];
  let each0_lookup = /* @__PURE__ */ new Map();
  let t;
  let each_blocks = [];
  let each1_lookup = /* @__PURE__ */ new Map();
  let each_value_3 = ctx[3];
  const get_key = (ctx2) => ctx2[11].id;
  for (let i = 0; i < each_value_3.length; i += 1) {
    let child_ctx = get_each_context_3$2(ctx, each_value_3, i);
    let key = get_key(child_ctx);
    each0_lookup.set(key, each_blocks_1[i] = create_each_block_3$2(key, child_ctx));
  }
  let each_value = ctx[3];
  const get_key_1 = (ctx2) => ctx2[11].id;
  for (let i = 0; i < each_value.length; i += 1) {
    let child_ctx = get_each_context$2(ctx, each_value, i);
    let key = get_key_1(child_ctx);
    each1_lookup.set(key, each_blocks[i] = create_each_block$2(key, child_ctx));
  }
  return {
    c() {
      div1 = element("div");
      div0 = element("div");
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].c();
      }
      t = space();
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      attr(div0, "class", "flex flex-row space-x-2");
      attr(div1, "class", "bg-white p-2 rounded");
    },
    m(target, anchor) {
      insert(target, div1, anchor);
      append(div1, div0);
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].m(div0, null);
      }
      append(div1, t);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(div1, null);
      }
    },
    p(ctx2, [dirty]) {
      if (dirty & 12) {
        each_value_3 = ctx2[3];
        each_blocks_1 = update_keyed_each(each_blocks_1, dirty, get_key, 1, ctx2, each_value_3, each0_lookup, div0, destroy_block, create_each_block_3$2, null, get_each_context_3$2);
      }
      if (dirty & 31) {
        each_value = ctx2[3];
        each_blocks = update_keyed_each(each_blocks, dirty, get_key_1, 1, ctx2, each_value, each1_lookup, div1, destroy_block, create_each_block$2, null, get_each_context$2);
      }
    },
    i: noop,
    o: noop,
    d(detaching) {
      if (detaching)
        detach(div1);
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].d();
      }
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].d();
      }
    }
  };
}
function instance$4($$self, $$props, $$invalidate) {
  const defaultConfig = {
    names: { permission: "mc_metabox_permission" },
    selections: {},
    i18n: {}
  };
  let { config = {
    permissions: [],
    selections: {},
    i18n: {}
  } } = $$props;
  const finalConfig = __spreadValues(__spreadValues({}, defaultConfig), config || {});
  if (!finalConfig.selections) {
    finalConfig.selections = {};
  }
  doDefault(finalConfig.permissions, finalConfig.selections || {}, (x) => x.id, (src) => ({
    id: src.id,
    name: src.name,
    post_types: [],
    capabilities: []
  }));
  Object.keys(finalConfig.selections || {}).forEach((x) => {
    const item = finalConfig.selections[x];
    item.post_types || (item.post_types = []);
    item.capabilities || (item.capabilities = []);
  });
  const permissions = finalConfig.permissions;
  const selections = finalConfig.selections;
  const __ = doGetter(finalConfig.i18n || {});
  let currentTab = permissions[0];
  const $$binding_groups = [[], []];
  const click_handler = (index) => $$invalidate(2, currentTab = permissions[index]);
  function input_change_handler(item, index) {
    selections[item.id].post_types = get_binding_group_value($$binding_groups[1][index], this.__value, this.checked);
    $$invalidate(1, selections);
  }
  function input_change_handler_1(item, index) {
    selections[item.id].capabilities = get_binding_group_value($$binding_groups[0][index], this.__value, this.checked);
    $$invalidate(1, selections);
  }
  $$self.$$set = ($$props2) => {
    if ("config" in $$props2)
      $$invalidate(5, config = $$props2.config);
  };
  return [
    finalConfig,
    selections,
    currentTab,
    permissions,
    __,
    config,
    click_handler,
    input_change_handler,
    $$binding_groups,
    input_change_handler_1
  ];
}
class UserPermissions extends SvelteComponent {
  constructor(options) {
    super();
    init(this, options, instance$4, create_fragment$4, safe_not_equal, { config: 5 });
  }
}
function get_each_context$1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[28] = list[i];
  child_ctx[30] = i;
  return child_ctx;
}
function get_each_context_1$1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[31] = list[i];
  child_ctx[32] = list;
  child_ctx[33] = i;
  return child_ctx;
}
function get_each_context_2$1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[34] = list[i];
  return child_ctx;
}
function get_each_context_3$1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[34] = list[i];
  return child_ctx;
}
function get_each_context_4(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[39] = list[i];
  child_ctx[41] = i;
  return child_ctx;
}
function get_each_context_5(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[34] = list[i];
  return child_ctx;
}
function create_if_block_2$1(ctx) {
  let input;
  let input_name_value;
  let input_value_value;
  return {
    c() {
      input = element("input");
      attr(input, "type", "hidden");
      attr(input, "name", input_name_value = "permissions[" + ctx[33] + "][id]");
      input.value = input_value_value = ctx[31].id;
    },
    m(target, anchor) {
      insert(target, input, anchor);
    },
    p(ctx2, dirty) {
      if (dirty[0] & 1 && input_name_value !== (input_name_value = "permissions[" + ctx2[33] + "][id]")) {
        attr(input, "name", input_name_value);
      }
      if (dirty[0] & 1 && input_value_value !== (input_value_value = ctx2[31].id)) {
        input.value = input_value_value;
      }
    },
    d(detaching) {
      if (detaching)
        detach(input);
    }
  };
}
function create_each_block_5(ctx) {
  let label;
  let input;
  let input_name_value;
  let t0;
  let span;
  let t1_value = ctx[34] + "";
  let t1;
  let t2;
  let mounted;
  let dispose;
  ctx[14][0][ctx[33]] = [];
  function input_change_handler() {
    ctx[13].call(input, ctx[32], ctx[33]);
  }
  return {
    c() {
      label = element("label");
      input = element("input");
      t0 = space();
      span = element("span");
      t1 = text(t1_value);
      t2 = space();
      attr(input, "type", "checkbox");
      input.__value = ctx[34];
      input.value = input.__value;
      attr(input, "name", input_name_value = "permissions[" + ctx[33] + "][post_types][]");
      ctx[14][0][ctx[33]].push(input);
      attr(label, "class", "items-center");
    },
    m(target, anchor) {
      insert(target, label, anchor);
      append(label, input);
      input.checked = ~ctx[31].post_types.indexOf(input.__value);
      append(label, t0);
      append(label, span);
      append(span, t1);
      append(label, t2);
      if (!mounted) {
        dispose = listen(input, "change", input_change_handler);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (dirty[0] & 1 && input_name_value !== (input_name_value = "permissions[" + ctx[33] + "][post_types][]")) {
        attr(input, "name", input_name_value);
      }
      if (dirty[0] & 1) {
        input.checked = ~ctx[31].post_types.indexOf(input.__value);
      }
    },
    d(detaching) {
      if (detaching)
        detach(label);
      ctx[14][0][ctx[33]].splice(ctx[14][0][ctx[33]].indexOf(input), 1);
      mounted = false;
      dispose();
    }
  };
}
function create_each_block_4(ctx) {
  let div1;
  let input;
  let input_value_value;
  let input_name_value;
  let t0;
  let div0;
  let span0;
  let t1_value = ctx[39] + "";
  let t1;
  let t2;
  let span1;
  let t3;
  let mounted;
  let dispose;
  function click_handler_2() {
    return ctx[16](ctx[31], ctx[41], ctx[32], ctx[33]);
  }
  return {
    c() {
      div1 = element("div");
      input = element("input");
      t0 = space();
      div0 = element("div");
      span0 = element("span");
      t1 = text(t1_value);
      t2 = space();
      span1 = element("span");
      span1.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" class="cursor-pointer" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`;
      t3 = space();
      attr(input, "type", "hidden");
      input.value = input_value_value = ctx[39];
      attr(input, "name", input_name_value = "permissions[" + ctx[33] + "][capabilities][]");
      attr(span1, "class", "ml-2 font-bold");
      attr(div0, "class", "flex shadow items-center bg-blue-500 text-white fill-white rounded mr-1 px-1");
      attr(div1, "class", "w-auto");
    },
    m(target, anchor) {
      insert(target, div1, anchor);
      append(div1, input);
      append(div1, t0);
      append(div1, div0);
      append(div0, span0);
      append(span0, t1);
      append(div0, t2);
      append(div0, span1);
      append(div1, t3);
      if (!mounted) {
        dispose = listen(span1, "click", click_handler_2);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (dirty[0] & 1 && input_value_value !== (input_value_value = ctx[39])) {
        input.value = input_value_value;
      }
      if (dirty[0] & 1 && input_name_value !== (input_name_value = "permissions[" + ctx[33] + "][capabilities][]")) {
        attr(input, "name", input_name_value);
      }
      if (dirty[0] & 1 && t1_value !== (t1_value = ctx[39] + ""))
        set_data(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        detach(div1);
      mounted = false;
      dispose();
    }
  };
}
function create_each_block_3$1(ctx) {
  let label;
  let input;
  let input_value_value;
  let input_name_value;
  let input_checked_value;
  let t0;
  let span;
  let t1_value = ctx[34] + "";
  let t1;
  let t2;
  return {
    c() {
      label = element("label");
      input = element("input");
      t0 = space();
      span = element("span");
      t1 = text(t1_value);
      t2 = space();
      attr(input, "type", "checkbox");
      input.value = input_value_value = ctx[34];
      attr(input, "name", input_name_value = "defaults[user][" + ctx[33] + "][post_types][]");
      input.checked = input_checked_value = ctx[0].defaults.user[ctx[33]].post_types.includes(ctx[34]);
      attr(label, "class", "flex items-center");
    },
    m(target, anchor) {
      insert(target, label, anchor);
      append(label, input);
      append(label, t0);
      append(label, span);
      append(span, t1);
      append(label, t2);
    },
    p(ctx2, dirty) {
      if (dirty[0] & 1 && input_value_value !== (input_value_value = ctx2[34])) {
        input.value = input_value_value;
      }
      if (dirty[0] & 1 && input_name_value !== (input_name_value = "defaults[user][" + ctx2[33] + "][post_types][]")) {
        attr(input, "name", input_name_value);
      }
      if (dirty[0] & 1 && input_checked_value !== (input_checked_value = ctx2[0].defaults.user[ctx2[33]].post_types.includes(ctx2[34]))) {
        input.checked = input_checked_value;
      }
      if (dirty[0] & 1 && t1_value !== (t1_value = ctx2[34] + ""))
        set_data(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        detach(label);
    }
  };
}
function create_each_block_2$1(ctx) {
  let div;
  let input;
  let input_value_value;
  let input_name_value;
  let input_checked_value;
  let t0;
  let span;
  let t1_value = ctx[34] + "";
  let t1;
  let t2;
  return {
    c() {
      div = element("div");
      input = element("input");
      t0 = space();
      span = element("span");
      t1 = text(t1_value);
      t2 = space();
      attr(input, "type", "checkbox");
      input.value = input_value_value = ctx[34];
      attr(input, "name", input_name_value = "defaults[user][" + ctx[33] + "][capabilities][]");
      input.checked = input_checked_value = isCapabilityChecked(ctx[31].id, ctx[34], ctx[0].defaults.user);
      attr(div, "class", "items-center");
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, input);
      append(div, t0);
      append(div, span);
      append(span, t1);
      append(div, t2);
    },
    p(ctx2, dirty) {
      if (dirty[0] & 1 && input_value_value !== (input_value_value = ctx2[34])) {
        input.value = input_value_value;
      }
      if (dirty[0] & 1 && input_name_value !== (input_name_value = "defaults[user][" + ctx2[33] + "][capabilities][]")) {
        attr(input, "name", input_name_value);
      }
      if (dirty[0] & 1 && input_checked_value !== (input_checked_value = isCapabilityChecked(ctx2[31].id, ctx2[34], ctx2[0].defaults.user))) {
        input.checked = input_checked_value;
      }
      if (dirty[0] & 1 && t1_value !== (t1_value = ctx2[34] + ""))
        set_data(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        detach(div);
    }
  };
}
function create_if_block_1$2(ctx) {
  let div;
  let mounted;
  let dispose;
  function click_handler_3() {
    return ctx[19](ctx[33]);
  }
  return {
    c() {
      div = element("div");
      div.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="cursor-pointer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 15l-6 -6l-6 6h12"></path></svg>`;
      attr(div, "title", "Move up");
    },
    m(target, anchor) {
      insert(target, div, anchor);
      if (!mounted) {
        dispose = listen(div, "click", click_handler_3);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
    },
    d(detaching) {
      if (detaching)
        detach(div);
      mounted = false;
      dispose();
    }
  };
}
function create_if_block$3(ctx) {
  let div;
  let mounted;
  let dispose;
  function click_handler_4() {
    return ctx[20](ctx[33]);
  }
  return {
    c() {
      div = element("div");
      div.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="cursor-pointer" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M18 15l-6 -6l-6 6h12" transform="rotate(180 12 12)"></path></svg>`;
      attr(div, "title", "Move down");
    },
    m(target, anchor) {
      insert(target, div, anchor);
      if (!mounted) {
        dispose = listen(div, "click", click_handler_4);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
    },
    d(detaching) {
      if (detaching)
        detach(div);
      mounted = false;
      dispose();
    }
  };
}
function create_each_block_1$1(key_1, ctx) {
  let div20;
  let t0;
  let div15;
  let div5;
  let p0;
  let t2;
  let div4;
  let label0;
  let span0;
  let t4;
  let input0;
  let input0_name_value;
  let t5;
  let label1;
  let input1;
  let input1_name_value;
  let input1_checked_value;
  let t6;
  let span1;
  let t8;
  let div1;
  let span2;
  let t10;
  let div0;
  let t11;
  let div3;
  let span3;
  let t13;
  let input2;
  let t14;
  let div2;
  let t15;
  let div14;
  let div13;
  let p1;
  let t17;
  let div6;
  let t19;
  let div12;
  let div8;
  let p2;
  let t21;
  let div7;
  let input3;
  let input3_name_value;
  let t22;
  let input4;
  let input4_name_value;
  let t23;
  let t24;
  let div11;
  let div9;
  let t26;
  let div10;
  let t27;
  let div19;
  let div16;
  let t28;
  let t29;
  let div17;
  let t30;
  let div18;
  let t32;
  let mounted;
  let dispose;
  let if_block0 = ctx[31].id && create_if_block_2$1(ctx);
  function input0_input_handler() {
    ctx[12].call(input0, ctx[32], ctx[33]);
  }
  let each_value_5 = ctx[3];
  let each_blocks_3 = [];
  for (let i = 0; i < each_value_5.length; i += 1) {
    each_blocks_3[i] = create_each_block_5(get_each_context_5(ctx, each_value_5, i));
  }
  function keypress_handler(...args) {
    return ctx[15](ctx[33], ...args);
  }
  let each_value_4 = ctx[31].capabilities || [];
  let each_blocks_2 = [];
  for (let i = 0; i < each_value_4.length; i += 1) {
    each_blocks_2[i] = create_each_block_4(get_each_context_4(ctx, each_value_4, i));
  }
  function input3_input_handler() {
    ctx[17].call(input3, ctx[32], ctx[33]);
  }
  function input4_input_handler() {
    ctx[18].call(input4, ctx[32], ctx[33]);
  }
  let each_value_3 = ctx[31].post_types;
  let each_blocks_1 = [];
  for (let i = 0; i < each_value_3.length; i += 1) {
    each_blocks_1[i] = create_each_block_3$1(get_each_context_3$1(ctx, each_value_3, i));
  }
  let each_value_2 = ctx[31].capabilities || [];
  let each_blocks = [];
  for (let i = 0; i < each_value_2.length; i += 1) {
    each_blocks[i] = create_each_block_2$1(get_each_context_2$1(ctx, each_value_2, i));
  }
  let if_block1 = ctx[33] > 0 && create_if_block_1$2(ctx);
  let if_block2 = ctx[33] < ctx[0].permissions.length - 1 && create_if_block$3(ctx);
  function click_handler_5() {
    return ctx[21](ctx[33]);
  }
  return {
    key: key_1,
    first: null,
    c() {
      div20 = element("div");
      if (if_block0)
        if_block0.c();
      t0 = space();
      div15 = element("div");
      div5 = element("div");
      p0 = element("p");
      p0.textContent = `${ctx[4]("Configuration")}`;
      t2 = space();
      div4 = element("div");
      label0 = element("label");
      span0 = element("span");
      span0.textContent = `${ctx[4]("Name")}`;
      t4 = space();
      input0 = element("input");
      t5 = space();
      label1 = element("label");
      input1 = element("input");
      t6 = space();
      span1 = element("span");
      span1.textContent = `${ctx[4]("Signed required")}`;
      t8 = space();
      div1 = element("div");
      span2 = element("span");
      span2.textContent = `${ctx[4]("Post types")}`;
      t10 = space();
      div0 = element("div");
      for (let i = 0; i < each_blocks_3.length; i += 1) {
        each_blocks_3[i].c();
      }
      t11 = space();
      div3 = element("div");
      span3 = element("span");
      span3.textContent = `${ctx[4]("Capabilities")}`;
      t13 = space();
      input2 = element("input");
      t14 = space();
      div2 = element("div");
      for (let i = 0; i < each_blocks_2.length; i += 1) {
        each_blocks_2[i].c();
      }
      t15 = space();
      div14 = element("div");
      div13 = element("div");
      p1 = element("p");
      p1.textContent = `${ctx[4]("Default user settings")}`;
      t17 = space();
      div6 = element("div");
      div6.textContent = `${ctx[4]("permission_desc")}`;
      t19 = space();
      div12 = element("div");
      div8 = element("div");
      p2 = element("p");
      p2.textContent = `${ctx[4]("Posts")}`;
      t21 = space();
      div7 = element("div");
      input3 = element("input");
      t22 = space();
      input4 = element("input");
      t23 = space();
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].c();
      }
      t24 = space();
      div11 = element("div");
      div9 = element("div");
      div9.textContent = `${ctx[4]("Capabilities")}`;
      t26 = space();
      div10 = element("div");
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      t27 = space();
      div19 = element("div");
      div16 = element("div");
      if (if_block1)
        if_block1.c();
      t28 = space();
      if (if_block2)
        if_block2.c();
      t29 = space();
      div17 = element("div");
      t30 = space();
      div18 = element("div");
      div18.textContent = `${ctx[4]("Delete")}`;
      t32 = space();
      attr(p0, "class", "font-bold text-sm mb-2");
      attr(span0, "class", "font-bold");
      attr(input0, "type", "text");
      attr(input0, "name", input0_name_value = "permissions[" + ctx[33] + "][name]");
      attr(input0, "class", "w-auto");
      attr(label0, "class", "flex flex-col");
      attr(input1, "type", "checkbox");
      attr(input1, "name", input1_name_value = "permissions[" + ctx[33] + "][logged_required]'");
      input1.checked = input1_checked_value = ctx[31].logged_required === "on";
      attr(span2, "class", "font-bold");
      attr(span3, "class", "font-bold");
      attr(input2, "type", "text");
      attr(div2, "class", "flex flex-wrap");
      attr(div3, "class", "flex flex-col space-y-1 flex-1");
      attr(div4, "class", "flex flex-col space-y-1 px-3");
      attr(div5, "class", "flex-1");
      attr(p1, "class", "font-bold text-sm mb-2");
      attr(div6, "class", "ring-1 ring-amber-500 bg-white p-2 rounded");
      attr(p2, "class", "font-bold");
      attr(input3, "type", "hidden");
      attr(input3, "name", input3_name_value = "defaults[user][" + ctx[33] + "][id]");
      attr(input4, "type", "hidden");
      attr(input4, "name", input4_name_value = "defaults[user][" + ctx[33] + "][name]");
      attr(div9, "class", "font-bold");
      attr(div10, "class", "flex flex-row space-x-2");
      attr(div12, "class", "px-3");
      attr(div13, "class", "space-y-3");
      attr(div14, "class", "flex-1 space-y-3");
      attr(div15, "class", "flex space-x-2");
      attr(div16, "class", "flex flex-col ");
      attr(div17, "class", "flex-1");
      attr(div18, "class", "p-2 bg-red-500 rounded text-white cursor-pointer");
      attr(div19, "class", "flex items-center ");
      attr(div20, "class", "rounded bg-white p-2");
      this.first = div20;
    },
    m(target, anchor) {
      insert(target, div20, anchor);
      if (if_block0)
        if_block0.m(div20, null);
      append(div20, t0);
      append(div20, div15);
      append(div15, div5);
      append(div5, p0);
      append(div5, t2);
      append(div5, div4);
      append(div4, label0);
      append(label0, span0);
      append(label0, t4);
      append(label0, input0);
      set_input_value(input0, ctx[31].name);
      append(div4, t5);
      append(div4, label1);
      append(label1, input1);
      append(label1, t6);
      append(label1, span1);
      append(div4, t8);
      append(div4, div1);
      append(div1, span2);
      append(div1, t10);
      append(div1, div0);
      for (let i = 0; i < each_blocks_3.length; i += 1) {
        each_blocks_3[i].m(div0, null);
      }
      append(div4, t11);
      append(div4, div3);
      append(div3, span3);
      append(div3, t13);
      append(div3, input2);
      append(div3, t14);
      append(div3, div2);
      for (let i = 0; i < each_blocks_2.length; i += 1) {
        each_blocks_2[i].m(div2, null);
      }
      append(div15, t15);
      append(div15, div14);
      append(div14, div13);
      append(div13, p1);
      append(div13, t17);
      append(div13, div6);
      append(div13, t19);
      append(div13, div12);
      append(div12, div8);
      append(div8, p2);
      append(div8, t21);
      append(div8, div7);
      append(div7, input3);
      set_input_value(input3, ctx[31].id);
      append(div7, t22);
      append(div7, input4);
      set_input_value(input4, ctx[31].name);
      append(div7, t23);
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].m(div7, null);
      }
      append(div12, t24);
      append(div12, div11);
      append(div11, div9);
      append(div11, t26);
      append(div11, div10);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(div10, null);
      }
      append(div20, t27);
      append(div20, div19);
      append(div19, div16);
      if (if_block1)
        if_block1.m(div16, null);
      append(div16, t28);
      if (if_block2)
        if_block2.m(div16, null);
      append(div19, t29);
      append(div19, div17);
      append(div19, t30);
      append(div19, div18);
      append(div20, t32);
      if (!mounted) {
        dispose = [
          listen(input0, "input", input0_input_handler),
          listen(input2, "keypress", keypress_handler),
          listen(input3, "input", input3_input_handler),
          listen(input4, "input", input4_input_handler),
          listen(div18, "click", prevent_default(click_handler_5))
        ];
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (ctx[31].id) {
        if (if_block0) {
          if_block0.p(ctx, dirty);
        } else {
          if_block0 = create_if_block_2$1(ctx);
          if_block0.c();
          if_block0.m(div20, t0);
        }
      } else if (if_block0) {
        if_block0.d(1);
        if_block0 = null;
      }
      if (dirty[0] & 1 && input0_name_value !== (input0_name_value = "permissions[" + ctx[33] + "][name]")) {
        attr(input0, "name", input0_name_value);
      }
      if (dirty[0] & 1 && input0.value !== ctx[31].name) {
        set_input_value(input0, ctx[31].name);
      }
      if (dirty[0] & 1 && input1_name_value !== (input1_name_value = "permissions[" + ctx[33] + "][logged_required]'")) {
        attr(input1, "name", input1_name_value);
      }
      if (dirty[0] & 1 && input1_checked_value !== (input1_checked_value = ctx[31].logged_required === "on")) {
        input1.checked = input1_checked_value;
      }
      if (dirty[0] & 9) {
        each_value_5 = ctx[3];
        let i;
        for (i = 0; i < each_value_5.length; i += 1) {
          const child_ctx = get_each_context_5(ctx, each_value_5, i);
          if (each_blocks_3[i]) {
            each_blocks_3[i].p(child_ctx, dirty);
          } else {
            each_blocks_3[i] = create_each_block_5(child_ctx);
            each_blocks_3[i].c();
            each_blocks_3[i].m(div0, null);
          }
        }
        for (; i < each_blocks_3.length; i += 1) {
          each_blocks_3[i].d(1);
        }
        each_blocks_3.length = each_value_5.length;
      }
      if (dirty[0] & 1) {
        each_value_4 = ctx[31].capabilities || [];
        let i;
        for (i = 0; i < each_value_4.length; i += 1) {
          const child_ctx = get_each_context_4(ctx, each_value_4, i);
          if (each_blocks_2[i]) {
            each_blocks_2[i].p(child_ctx, dirty);
          } else {
            each_blocks_2[i] = create_each_block_4(child_ctx);
            each_blocks_2[i].c();
            each_blocks_2[i].m(div2, null);
          }
        }
        for (; i < each_blocks_2.length; i += 1) {
          each_blocks_2[i].d(1);
        }
        each_blocks_2.length = each_value_4.length;
      }
      if (dirty[0] & 1 && input3_name_value !== (input3_name_value = "defaults[user][" + ctx[33] + "][id]")) {
        attr(input3, "name", input3_name_value);
      }
      if (dirty[0] & 1) {
        set_input_value(input3, ctx[31].id);
      }
      if (dirty[0] & 1 && input4_name_value !== (input4_name_value = "defaults[user][" + ctx[33] + "][name]")) {
        attr(input4, "name", input4_name_value);
      }
      if (dirty[0] & 1) {
        set_input_value(input4, ctx[31].name);
      }
      if (dirty[0] & 1) {
        each_value_3 = ctx[31].post_types;
        let i;
        for (i = 0; i < each_value_3.length; i += 1) {
          const child_ctx = get_each_context_3$1(ctx, each_value_3, i);
          if (each_blocks_1[i]) {
            each_blocks_1[i].p(child_ctx, dirty);
          } else {
            each_blocks_1[i] = create_each_block_3$1(child_ctx);
            each_blocks_1[i].c();
            each_blocks_1[i].m(div7, null);
          }
        }
        for (; i < each_blocks_1.length; i += 1) {
          each_blocks_1[i].d(1);
        }
        each_blocks_1.length = each_value_3.length;
      }
      if (dirty[0] & 1) {
        each_value_2 = ctx[31].capabilities || [];
        let i;
        for (i = 0; i < each_value_2.length; i += 1) {
          const child_ctx = get_each_context_2$1(ctx, each_value_2, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
          } else {
            each_blocks[i] = create_each_block_2$1(child_ctx);
            each_blocks[i].c();
            each_blocks[i].m(div10, null);
          }
        }
        for (; i < each_blocks.length; i += 1) {
          each_blocks[i].d(1);
        }
        each_blocks.length = each_value_2.length;
      }
      if (ctx[33] > 0) {
        if (if_block1) {
          if_block1.p(ctx, dirty);
        } else {
          if_block1 = create_if_block_1$2(ctx);
          if_block1.c();
          if_block1.m(div16, t28);
        }
      } else if (if_block1) {
        if_block1.d(1);
        if_block1 = null;
      }
      if (ctx[33] < ctx[0].permissions.length - 1) {
        if (if_block2) {
          if_block2.p(ctx, dirty);
        } else {
          if_block2 = create_if_block$3(ctx);
          if_block2.c();
          if_block2.m(div16, null);
        }
      } else if (if_block2) {
        if_block2.d(1);
        if_block2 = null;
      }
    },
    d(detaching) {
      if (detaching)
        detach(div20);
      if (if_block0)
        if_block0.d();
      destroy_each(each_blocks_3, detaching);
      destroy_each(each_blocks_2, detaching);
      destroy_each(each_blocks_1, detaching);
      destroy_each(each_blocks, detaching);
      if (if_block1)
        if_block1.d();
      if (if_block2)
        if_block2.d();
      mounted = false;
      run_all(dispose);
    }
  };
}
function create_each_block$1(ctx) {
  let input;
  let input_value_value;
  let t0;
  let div;
  let span0;
  let t1_value = ctx[28] + "";
  let t1;
  let t2;
  let span1;
  let t3;
  let mounted;
  let dispose;
  function click_handler_6() {
    return ctx[23](ctx[30]);
  }
  return {
    c() {
      input = element("input");
      t0 = space();
      div = element("div");
      span0 = element("span");
      t1 = text(t1_value);
      t2 = space();
      span1 = element("span");
      span1.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="cursor-pointer"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`;
      t3 = space();
      attr(input, "type", "hidden");
      attr(input, "name", "countries[]");
      input.value = input_value_value = ctx[28];
      attr(span1, "class", "ml-2 font-bold");
      attr(div, "class", "flex shadow items-center bg-blue-500 text-white fill-white rounded mr-1 px-1 mt-1");
    },
    m(target, anchor) {
      insert(target, input, anchor);
      insert(target, t0, anchor);
      insert(target, div, anchor);
      append(div, span0);
      append(span0, t1);
      append(div, t2);
      append(div, span1);
      append(div, t3);
      if (!mounted) {
        dispose = listen(span1, "click", click_handler_6);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (dirty[0] & 1 && input_value_value !== (input_value_value = ctx[28])) {
        input.value = input_value_value;
      }
      if (dirty[0] & 1 && t1_value !== (t1_value = ctx[28] + ""))
        set_data(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        detach(input);
      if (detaching)
        detach(t0);
      if (detaching)
        detach(div);
      mounted = false;
      dispose();
    }
  };
}
function create_fragment$3(ctx) {
  let div3;
  let div0;
  let button0;
  let t1;
  let button1;
  let t3;
  let form;
  let details0;
  let summary0;
  let t5;
  let each_blocks_1 = [];
  let each0_lookup = /* @__PURE__ */ new Map();
  let t6;
  let details1;
  let summary1;
  let t8;
  let div2;
  let input;
  let t9;
  let div1;
  let mounted;
  let dispose;
  let each_value_1 = ctx[0].permissions;
  const get_key = (ctx2) => ctx2[31].id;
  for (let i = 0; i < each_value_1.length; i += 1) {
    let child_ctx = get_each_context_1$1(ctx, each_value_1, i);
    let key = get_key(child_ctx);
    each0_lookup.set(key, each_blocks_1[i] = create_each_block_1$1(key, child_ctx));
  }
  let each_value = ctx[0].countries;
  let each_blocks = [];
  for (let i = 0; i < each_value.length; i += 1) {
    each_blocks[i] = create_each_block$1(get_each_context$1(ctx, each_value, i));
  }
  return {
    c() {
      div3 = element("div");
      div0 = element("div");
      button0 = element("button");
      button0.textContent = `${ctx[4]("Save settings")}`;
      t1 = space();
      button1 = element("button");
      button1.textContent = `${ctx[4]("Add permission")}`;
      t3 = space();
      form = element("form");
      details0 = element("details");
      summary0 = element("summary");
      summary0.textContent = `${ctx[4]("Permissions")}`;
      t5 = space();
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].c();
      }
      t6 = space();
      details1 = element("details");
      summary1 = element("summary");
      summary1.textContent = `${ctx[4]("Countries")}`;
      t8 = space();
      div2 = element("div");
      input = element("input");
      t9 = space();
      div1 = element("div");
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      attr(button0, "class", "bg-blue-500 text-white rounded p-1 font-bold");
      attr(button1, "class", "bg-blue-500 rounded p-1 text-white shadow font-bold");
      attr(div0, "class", "flex space-x-2 mt-2");
      details0.open = true;
      attr(input, "type", "text");
      attr(input, "placeholder", ctx[4]("Add country"));
      attr(div1, "class", "flex flex-wrap");
      attr(div2, "class", "rounded bg-white p-2 ");
      details1.open = true;
      attr(form, "action", ctx[2]);
      attr(form, "method", "post");
      attr(form, "class", "space-y-2");
      attr(div3, "class", "space-y-2 text-gray-600");
    },
    m(target, anchor) {
      insert(target, div3, anchor);
      append(div3, div0);
      append(div0, button0);
      append(div0, t1);
      append(div0, button1);
      append(div3, t3);
      append(div3, form);
      append(form, details0);
      append(details0, summary0);
      append(details0, t5);
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].m(details0, null);
      }
      append(form, t6);
      append(form, details1);
      append(details1, summary1);
      append(details1, t8);
      append(details1, div2);
      append(div2, input);
      append(div2, t9);
      append(div2, div1);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(div1, null);
      }
      ctx[24](form);
      if (!mounted) {
        dispose = [
          listen(button0, "click", ctx[10]),
          listen(button1, "click", ctx[11]),
          listen(input, "change", ctx[22])
        ];
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty[0] & 505) {
        each_value_1 = ctx2[0].permissions;
        each_blocks_1 = update_keyed_each(each_blocks_1, dirty, get_key, 1, ctx2, each_value_1, each0_lookup, details0, destroy_block, create_each_block_1$1, null, get_each_context_1$1);
      }
      if (dirty[0] & 1) {
        each_value = ctx2[0].countries;
        let i;
        for (i = 0; i < each_value.length; i += 1) {
          const child_ctx = get_each_context$1(ctx2, each_value, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
          } else {
            each_blocks[i] = create_each_block$1(child_ctx);
            each_blocks[i].c();
            each_blocks[i].m(div1, null);
          }
        }
        for (; i < each_blocks.length; i += 1) {
          each_blocks[i].d(1);
        }
        each_blocks.length = each_value.length;
      }
    },
    i: noop,
    o: noop,
    d(detaching) {
      if (detaching)
        detach(div3);
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].d();
      }
      destroy_each(each_blocks, detaching);
      ctx[24](null);
      mounted = false;
      run_all(dispose);
    }
  };
}
function isCapabilityChecked(index, pt, items) {
  const tmp = items.find((x) => x.id === index);
  return tmp && tmp.capabilities.includes(pt);
}
function instance$3($$self, $$props, $$invalidate) {
  let { config = {
    postUrl: "",
    defaultPermission: {
      name: "New item",
      logged_required: true,
      post_types: [],
      capabilities: []
    },
    post_types: ["post", "page", "attachment"],
    permissions: [
      {
        name: "ShowProDir",
        logged_required: false,
        post_types: ["attachment"],
        capabilities: ["CAN_READ", "CAN_WRITE"]
      }
    ],
    defaults: {
      post: { permissions: [], capabilities: [] },
      user: { permissions: [], capabilities: [] }
    },
    i18n: {}
  } } = $$props;
  config.defaults || (config.defaults = {});
  config.defaults.user || (config.defaults.user = [...config.permissions]);
  config.defaults.post || (config.defaults.post = [...config.permissions]);
  config.countries || (config.countries = []);
  doDefault2(config.defaults.post);
  doDefault2(config.defaults.user);
  const postUrl = config.postUrl;
  const defaultPermission = config.defaultPermission;
  const postTypes = config.post_types;
  config.permissions;
  const __ = doGetter(config.i18n);
  let formRef;
  function doDefault2(perTmp) {
    for (let i = 0; i < config.permissions.length; i++) {
      perTmp[i] = perTmp[i] || config.permissions[i];
      perTmp[i].post_types = perTmp[i].post_types || [];
      perTmp[i].capabilities = perTmp[i].capabilities || [];
    }
  }
  function onKeypress(e, index) {
    if (e.key === "Enter") {
      e.preventDefault();
      if (e.target.value === "")
        return;
      $$invalidate(0, config.permissions[index].capabilities = config.permissions[index].capabilities || [], config);
      $$invalidate(0, config.permissions[index].capabilities = [...config.permissions[index].capabilities, e.target.value], config);
      e.target.value = "";
    }
  }
  function removePermission(index) {
    config.permissions.splice(index, 1);
    $$invalidate(0, config);
  }
  function moveUp(index) {
    const tmp = config.permissions[index - 1];
    $$invalidate(0, config.permissions[index - 1] = config.permissions[index], config);
    $$invalidate(0, config.permissions[index] = tmp, config);
  }
  function moveDown(index) {
    const tmp = config.permissions[index + 1];
    $$invalidate(0, config.permissions[index + 1] = config.permissions[index], config);
    $$invalidate(0, config.permissions[index] = tmp, config);
  }
  function addNew() {
    $$invalidate(0, config.permissions = [__spreadProps(__spreadValues({}, defaultPermission), { id: Date.now() }), ...config.permissions], config);
    doDefault2(config.defaults.user);
  }
  const $$binding_groups = [[]];
  const click_handler = () => formRef.submit();
  const click_handler_1 = () => addNew();
  function input0_input_handler(each_value_1, index) {
    each_value_1[index].name = this.value;
    $$invalidate(0, config);
  }
  function input_change_handler(each_value_1, index) {
    each_value_1[index].post_types = get_binding_group_value($$binding_groups[0][index], this.__value, this.checked);
    $$invalidate(0, config);
  }
  const keypress_handler = (index, e) => onKeypress(e, index);
  const click_handler_2 = (permission, capIndex, each_value_1, index) => {
    permission.capabilities.splice(capIndex, 1);
    $$invalidate(0, each_value_1[index].capabilities = permission.capabilities, config);
  };
  function input3_input_handler(each_value_1, index) {
    each_value_1[index].id = this.value;
    $$invalidate(0, config);
  }
  function input4_input_handler(each_value_1, index) {
    each_value_1[index].name = this.value;
    $$invalidate(0, config);
  }
  const click_handler_3 = (index) => moveUp(index);
  const click_handler_4 = (index) => moveDown(index);
  const click_handler_5 = (index) => removePermission(index);
  const change_handler = (e) => {
    if (e.target.value.length > 0) {
      config.countries.push(e.target.value);
      $$invalidate(0, config);
    }
  };
  const click_handler_6 = (countryIndex) => {
    config.countries.splice(countryIndex, 1);
    $$invalidate(0, config);
  };
  function form_binding($$value) {
    binding_callbacks[$$value ? "unshift" : "push"](() => {
      formRef = $$value;
      $$invalidate(1, formRef);
    });
  }
  $$self.$$set = ($$props2) => {
    if ("config" in $$props2)
      $$invalidate(0, config = $$props2.config);
  };
  return [
    config,
    formRef,
    postUrl,
    postTypes,
    __,
    onKeypress,
    removePermission,
    moveUp,
    moveDown,
    addNew,
    click_handler,
    click_handler_1,
    input0_input_handler,
    input_change_handler,
    $$binding_groups,
    keypress_handler,
    click_handler_2,
    input3_input_handler,
    input4_input_handler,
    click_handler_3,
    click_handler_4,
    click_handler_5,
    change_handler,
    click_handler_6,
    form_binding
  ];
}
class PermissionEditor extends SvelteComponent {
  constructor(options) {
    super();
    init(this, options, instance$3, create_fragment$3, safe_not_equal, { config: 0 }, null, [-1, -1]);
  }
}
function get_each_context(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[10] = list[i];
  child_ctx[12] = i;
  return child_ctx;
}
function get_each_context_1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[13] = list[i];
  child_ctx[14] = list;
  child_ctx[12] = i;
  return child_ctx;
}
function get_each_context_2(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[15] = list[i];
  child_ctx[17] = i;
  return child_ctx;
}
function get_each_context_3(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[13] = list[i];
  child_ctx[12] = i;
  return child_ctx;
}
function create_if_block$2(ctx) {
  let div2;
  let b;
  let t1;
  let div1;
  let div0;
  let t2;
  let each_value_3 = ctx[0].permissions;
  let each_blocks_1 = [];
  for (let i = 0; i < each_value_3.length; i += 1) {
    each_blocks_1[i] = create_each_block_3(get_each_context_3(ctx, each_value_3, i));
  }
  let each_value_1 = ctx[0].permissions;
  let each_blocks = [];
  for (let i = 0; i < each_value_1.length; i += 1) {
    each_blocks[i] = create_each_block_1(get_each_context_1(ctx, each_value_1, i));
  }
  return {
    c() {
      div2 = element("div");
      b = element("b");
      b.textContent = `${ctx[3]("Permissions")}`;
      t1 = space();
      div1 = element("div");
      div0 = element("div");
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].c();
      }
      t2 = space();
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      attr(div0, "class", "flex flex-row space-x-2");
      attr(div1, "class", "p-2 bg-white space-y-2");
      attr(div2, "class", "ring-1 ring-gray-300 p-2 rounded");
    },
    m(target, anchor) {
      insert(target, div2, anchor);
      append(div2, b);
      append(div2, t1);
      append(div2, div1);
      append(div1, div0);
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].m(div0, null);
      }
      append(div1, t2);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(div1, null);
      }
    },
    p(ctx2, dirty) {
      if (dirty & 5) {
        each_value_3 = ctx2[0].permissions;
        let i;
        for (i = 0; i < each_value_3.length; i += 1) {
          const child_ctx = get_each_context_3(ctx2, each_value_3, i);
          if (each_blocks_1[i]) {
            each_blocks_1[i].p(child_ctx, dirty);
          } else {
            each_blocks_1[i] = create_each_block_3(child_ctx);
            each_blocks_1[i].c();
            each_blocks_1[i].m(div0, null);
          }
        }
        for (; i < each_blocks_1.length; i += 1) {
          each_blocks_1[i].d(1);
        }
        each_blocks_1.length = each_value_3.length;
      }
      if (dirty & 15) {
        each_value_1 = ctx2[0].permissions;
        let i;
        for (i = 0; i < each_value_1.length; i += 1) {
          const child_ctx = get_each_context_1(ctx2, each_value_1, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
          } else {
            each_blocks[i] = create_each_block_1(child_ctx);
            each_blocks[i].c();
            each_blocks[i].m(div1, null);
          }
        }
        for (; i < each_blocks.length; i += 1) {
          each_blocks[i].d(1);
        }
        each_blocks.length = each_value_1.length;
      }
    },
    d(detaching) {
      if (detaching)
        detach(div2);
      destroy_each(each_blocks_1, detaching);
      destroy_each(each_blocks, detaching);
    }
  };
}
function create_each_block_3(ctx) {
  let div;
  let t0_value = ctx[13].name + "";
  let t0;
  let t1;
  let div_class_value;
  let mounted;
  let dispose;
  function click_handler() {
    return ctx[5](ctx[12]);
  }
  return {
    c() {
      div = element("div");
      t0 = text(t0_value);
      t1 = space();
      attr(div, "class", div_class_value = "shadow font-bold hover:bg-blue-500 hover:text-white cursor-pointer rounded flex-1 p-2 " + (ctx[2].name === ctx[13].name && "bg-blue-500 text-white"));
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, t0);
      append(div, t1);
      if (!mounted) {
        dispose = listen(div, "click", click_handler);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (dirty & 1 && t0_value !== (t0_value = ctx[13].name + ""))
        set_data(t0, t0_value);
      if (dirty & 5 && div_class_value !== (div_class_value = "shadow font-bold hover:bg-blue-500 hover:text-white cursor-pointer rounded flex-1 p-2 " + (ctx[2].name === ctx[13].name && "bg-blue-500 text-white"))) {
        attr(div, "class", div_class_value);
      }
    },
    d(detaching) {
      if (detaching)
        detach(div);
      mounted = false;
      dispose();
    }
  };
}
function create_else_block$1(ctx) {
  let svg;
  let path;
  let line0;
  let line1;
  return {
    c() {
      svg = svg_element("svg");
      path = svg_element("path");
      line0 = svg_element("line");
      line1 = svg_element("line");
      attr(path, "stroke", "none");
      attr(path, "d", "M0 0h24v24H0z");
      attr(path, "fill", "none");
      attr(line0, "x1", "18");
      attr(line0, "y1", "6");
      attr(line0, "x2", "6");
      attr(line0, "y2", "18");
      attr(line1, "x1", "6");
      attr(line1, "y1", "6");
      attr(line1, "x2", "18");
      attr(line1, "y2", "18");
      attr(svg, "xmlns", "http://www.w3.org/2000/svg");
      attr(svg, "width", "24");
      attr(svg, "height", "24");
      attr(svg, "viewBox", "0 0 24 24");
      attr(svg, "stroke-width", "2");
      attr(svg, "stroke", "currentColor");
      attr(svg, "fill", "none");
      attr(svg, "stroke-linecap", "round");
      attr(svg, "stroke-linejoin", "round");
    },
    m(target, anchor) {
      insert(target, svg, anchor);
      append(svg, path);
      append(svg, line0);
      append(svg, line1);
    },
    d(detaching) {
      if (detaching)
        detach(svg);
    }
  };
}
function create_if_block_1$1(ctx) {
  let svg;
  let path0;
  let path1;
  let path2;
  return {
    c() {
      svg = svg_element("svg");
      path0 = svg_element("path");
      path1 = svg_element("path");
      path2 = svg_element("path");
      attr(path0, "stroke", "none");
      attr(path0, "d", "M0 0h24v24H0z");
      attr(path0, "fill", "none");
      attr(path1, "d", "M7 12l5 5l10 -10");
      attr(path2, "d", "M2 12l5 5m5 -5l5 -5");
      attr(svg, "xmlns", "http://www.w3.org/2000/svg");
      attr(svg, "width", "24");
      attr(svg, "height", "24");
      attr(svg, "viewBox", "0 0 24 24");
      attr(svg, "stroke-width", "2");
      attr(svg, "stroke", "currentColor");
      attr(svg, "fill", "none");
      attr(svg, "stroke-linecap", "round");
      attr(svg, "stroke-linejoin", "round");
    },
    m(target, anchor) {
      insert(target, svg, anchor);
      append(svg, path0);
      append(svg, path1);
      append(svg, path2);
    },
    d(detaching) {
      if (detaching)
        detach(svg);
    }
  };
}
function create_each_block_2(ctx) {
  let label;
  let input;
  let input_value_value;
  let input_name_value;
  let t0;
  let span;
  let t1_value = ctx[15] + "";
  let t1;
  let t2;
  let mounted;
  let dispose;
  ctx[7][1][ctx[12]] = [];
  function input_change_handler() {
    ctx[6].call(input, ctx[13], ctx[12]);
  }
  return {
    c() {
      label = element("label");
      input = element("input");
      t0 = space();
      span = element("span");
      t1 = text(t1_value);
      t2 = space();
      attr(input, "type", "checkbox");
      input.__value = input_value_value = ctx[15];
      input.value = input.__value;
      attr(input, "name", input_name_value = ctx[0].names.permission + "[" + ctx[13].id + "][capabilities][]");
      ctx[7][1][ctx[12]].push(input);
    },
    m(target, anchor) {
      insert(target, label, anchor);
      append(label, input);
      input.checked = ~ctx[1][ctx[13].id].capabilities.indexOf(input.__value);
      append(label, t0);
      append(label, span);
      append(span, t1);
      append(label, t2);
      if (!mounted) {
        dispose = listen(input, "change", input_change_handler);
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (dirty & 1 && input_value_value !== (input_value_value = ctx[15])) {
        input.__value = input_value_value;
        input.value = input.__value;
      }
      if (dirty & 1 && input_name_value !== (input_name_value = ctx[0].names.permission + "[" + ctx[13].id + "][capabilities][]")) {
        attr(input, "name", input_name_value);
      }
      if (dirty & 3) {
        input.checked = ~ctx[1][ctx[13].id].capabilities.indexOf(input.__value);
      }
      if (dirty & 1 && t1_value !== (t1_value = ctx[15] + ""))
        set_data(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        detach(label);
      ctx[7][1][ctx[12]].splice(ctx[7][1][ctx[12]].indexOf(input), 1);
      mounted = false;
      dispose();
    }
  };
}
function create_each_block_1(ctx) {
  let div2;
  let div0;
  let span0;
  let t1;
  let t2;
  let div1;
  let span1;
  let t4;
  let t5;
  function select_block_type(ctx2, dirty) {
    if (ctx2[13].logged_required)
      return create_if_block_1$1;
    return create_else_block$1;
  }
  let current_block_type = select_block_type(ctx);
  let if_block = current_block_type(ctx);
  let each_value_2 = ctx[13].capabilities || [];
  let each_blocks = [];
  for (let i = 0; i < each_value_2.length; i += 1) {
    each_blocks[i] = create_each_block_2(get_each_context_2(ctx, each_value_2, i));
  }
  return {
    c() {
      div2 = element("div");
      div0 = element("div");
      span0 = element("span");
      span0.textContent = `${ctx[3]("Signed required")}`;
      t1 = space();
      if_block.c();
      t2 = space();
      div1 = element("div");
      span1 = element("span");
      span1.textContent = `${ctx[3]("Capabilities")}`;
      t4 = space();
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      t5 = space();
      attr(div0, "class", "flex items-center");
      attr(div1, "class", "flex items-center space-x-2");
      attr(div2, "class", "p-1");
      toggle_class(div2, "hidden", ctx[13].name !== ctx[2].name);
    },
    m(target, anchor) {
      insert(target, div2, anchor);
      append(div2, div0);
      append(div0, span0);
      append(div0, t1);
      if_block.m(div0, null);
      append(div2, t2);
      append(div2, div1);
      append(div1, span1);
      append(div1, t4);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(div1, null);
      }
      append(div2, t5);
    },
    p(ctx2, dirty) {
      if (current_block_type !== (current_block_type = select_block_type(ctx2))) {
        if_block.d(1);
        if_block = current_block_type(ctx2);
        if (if_block) {
          if_block.c();
          if_block.m(div0, null);
        }
      }
      if (dirty & 3) {
        each_value_2 = ctx2[13].capabilities || [];
        let i;
        for (i = 0; i < each_value_2.length; i += 1) {
          const child_ctx = get_each_context_2(ctx2, each_value_2, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
          } else {
            each_blocks[i] = create_each_block_2(child_ctx);
            each_blocks[i].c();
            each_blocks[i].m(div1, null);
          }
        }
        for (; i < each_blocks.length; i += 1) {
          each_blocks[i].d(1);
        }
        each_blocks.length = each_value_2.length;
      }
      if (dirty & 5) {
        toggle_class(div2, "hidden", ctx2[13].name !== ctx2[2].name);
      }
    },
    d(detaching) {
      if (detaching)
        detach(div2);
      if_block.d();
      destroy_each(each_blocks, detaching);
    }
  };
}
function create_each_block(ctx) {
  let label;
  let input;
  let input_value_value;
  let input_name_value;
  let t0;
  let t1_value = ctx[10] + "";
  let t1;
  let t2;
  let mounted;
  let dispose;
  return {
    c() {
      label = element("label");
      input = element("input");
      t0 = space();
      t1 = text(t1_value);
      t2 = space();
      attr(input, "type", "checkbox");
      input.__value = input_value_value = ctx[10];
      input.value = input.__value;
      attr(input, "name", input_name_value = ctx[0].names.countries + "[]");
      ctx[7][0].push(input);
    },
    m(target, anchor) {
      insert(target, label, anchor);
      append(label, input);
      input.checked = ~ctx[0].countries.indexOf(input.__value);
      append(label, t0);
      append(label, t1);
      append(label, t2);
      if (!mounted) {
        dispose = listen(input, "change", ctx[8]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 1 && input_value_value !== (input_value_value = ctx2[10])) {
        input.__value = input_value_value;
        input.value = input.__value;
      }
      if (dirty & 1 && input_name_value !== (input_name_value = ctx2[0].names.countries + "[]")) {
        attr(input, "name", input_name_value);
      }
      if (dirty & 1) {
        input.checked = ~ctx2[0].countries.indexOf(input.__value);
      }
      if (dirty & 1 && t1_value !== (t1_value = ctx2[10] + ""))
        set_data(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        detach(label);
      ctx[7][0].splice(ctx[7][0].indexOf(input), 1);
      mounted = false;
      dispose();
    }
  };
}
function create_fragment$2(ctx) {
  let div4;
  let div0;
  let label0;
  let b0;
  let t1;
  let textarea;
  let textarea_name_value;
  let textarea_value_value;
  let t2;
  let div1;
  let label1;
  let b1;
  let t4;
  let input;
  let input_name_value;
  let t5;
  let t6;
  let div3;
  let b2;
  let t8;
  let div2;
  let if_block = ctx[2] && create_if_block$2(ctx);
  let each_value = ctx[0].base_countries;
  let each_blocks = [];
  for (let i = 0; i < each_value.length; i += 1) {
    each_blocks[i] = create_each_block(get_each_context(ctx, each_value, i));
  }
  return {
    c() {
      div4 = element("div");
      div0 = element("div");
      label0 = element("label");
      b0 = element("b");
      b0.textContent = `${ctx[3]("Abstract")}`;
      t1 = space();
      textarea = element("textarea");
      t2 = space();
      div1 = element("div");
      label1 = element("label");
      b1 = element("b");
      b1.textContent = `${ctx[3]("Decorative image")}`;
      t4 = space();
      input = element("input");
      t5 = space();
      if (if_block)
        if_block.c();
      t6 = space();
      div3 = element("div");
      b2 = element("b");
      b2.textContent = `${ctx[3]("Countries")}`;
      t8 = space();
      div2 = element("div");
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      attr(textarea, "name", textarea_name_value = ctx[0].names.abstract);
      attr(textarea, "class", "w-full");
      textarea.value = textarea_value_value = ctx[0].abstract;
      attr(label0, "class", "space-y-1");
      attr(div0, "class", "flex flex-col ring-1 ring-gray-300 p-2 rounded");
      attr(input, "type", "file");
      attr(input, "accept", "image/png, image/jpeg");
      attr(input, "name", input_name_value = ctx[0].names.image);
      attr(div1, "class", "flex flex-col ring-1 ring-gray-300 p-2 rounded");
      attr(div2, "class", "flex flex-row flex-wrap space-x-2");
      attr(div3, "class", "ring-1 ring-gray-300 p-2 rounded");
      attr(div4, "class", "space-y-2");
    },
    m(target, anchor) {
      insert(target, div4, anchor);
      append(div4, div0);
      append(div0, label0);
      append(label0, b0);
      append(label0, t1);
      append(label0, textarea);
      append(div4, t2);
      append(div4, div1);
      append(div1, label1);
      append(label1, b1);
      append(label1, t4);
      append(label1, input);
      append(div4, t5);
      if (if_block)
        if_block.m(div4, null);
      append(div4, t6);
      append(div4, div3);
      append(div3, b2);
      append(div3, t8);
      append(div3, div2);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(div2, null);
      }
    },
    p(ctx2, [dirty]) {
      if (dirty & 1 && textarea_name_value !== (textarea_name_value = ctx2[0].names.abstract)) {
        attr(textarea, "name", textarea_name_value);
      }
      if (dirty & 1 && textarea_value_value !== (textarea_value_value = ctx2[0].abstract)) {
        textarea.value = textarea_value_value;
      }
      if (dirty & 1 && input_name_value !== (input_name_value = ctx2[0].names.image)) {
        attr(input, "name", input_name_value);
      }
      if (ctx2[2]) {
        if (if_block) {
          if_block.p(ctx2, dirty);
        } else {
          if_block = create_if_block$2(ctx2);
          if_block.c();
          if_block.m(div4, t6);
        }
      } else if (if_block) {
        if_block.d(1);
        if_block = null;
      }
      if (dirty & 1) {
        each_value = ctx2[0].base_countries;
        let i;
        for (i = 0; i < each_value.length; i += 1) {
          const child_ctx = get_each_context(ctx2, each_value, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
          } else {
            each_blocks[i] = create_each_block(child_ctx);
            each_blocks[i].c();
            each_blocks[i].m(div2, null);
          }
        }
        for (; i < each_blocks.length; i += 1) {
          each_blocks[i].d(1);
        }
        each_blocks.length = each_value.length;
      }
    },
    i: noop,
    o: noop,
    d(detaching) {
      if (detaching)
        detach(div4);
      if (if_block)
        if_block.d();
      destroy_each(each_blocks, detaching);
    }
  };
}
function instance$2($$self, $$props, $$invalidate) {
  const defaultConfig = {
    names: {
      abstract: "mc_metabox_abstract",
      image: "mc_metabox_image",
      permission: "mc_metabox_permission",
      countries: "mc_metabox_countries"
    },
    abstract: "",
    permissions: [],
    selections: {},
    i18n: {}
  };
  let { config = {} } = $$props;
  config.permissions || (config.permissions = []);
  config.base_countries || (config.base_countries = []);
  config.countries || (config.countries = []);
  const finalConfig = __spreadValues(__spreadValues({}, defaultConfig), config || {});
  console.log(finalConfig);
  doDefault(finalConfig.permissions, finalConfig.selections || [], (x) => x.id, (src) => ({
    id: src.id,
    name: src.name,
    post_types: [],
    capabilities: []
  }));
  const selections = arrayAsMap(finalConfig.selections, (x) => x.id);
  const __ = doGetter(finalConfig.i18n);
  let currentTab = finalConfig.permissions[0];
  const $$binding_groups = [[], []];
  const click_handler = (index) => $$invalidate(2, currentTab = finalConfig.permissions[index]);
  function input_change_handler(item, index) {
    selections[item.id].capabilities = get_binding_group_value($$binding_groups[1][index], this.__value, this.checked);
    $$invalidate(1, selections);
  }
  function input_change_handler_1() {
    finalConfig.countries = get_binding_group_value($$binding_groups[0], this.__value, this.checked);
    $$invalidate(0, finalConfig);
  }
  $$self.$$set = ($$props2) => {
    if ("config" in $$props2)
      $$invalidate(4, config = $$props2.config);
  };
  return [
    finalConfig,
    selections,
    currentTab,
    __,
    config,
    click_handler,
    input_change_handler,
    $$binding_groups,
    input_change_handler_1
  ];
}
class MetaPost extends SvelteComponent {
  constructor(options) {
    super();
    init(this, options, instance$2, create_fragment$2, safe_not_equal, { config: 4 });
  }
}
function cubicOut(t) {
  const f = t - 1;
  return f * f * f + 1;
}
function fly(node, { delay = 0, duration = 400, easing = cubicOut, x = 0, y = 0, opacity = 0 } = {}) {
  const style = getComputedStyle(node);
  const target_opacity = +style.opacity;
  const transform = style.transform === "none" ? "" : style.transform;
  const od = target_opacity * (1 - opacity);
  return {
    delay,
    duration,
    easing,
    css: (t, u) => `
			transform: ${transform} translate(${(1 - t) * x}px, ${(1 - t) * y}px);
			opacity: ${target_opacity - od * u}`
  };
}
function create_if_block$1(ctx) {
  let div;
  let t;
  return {
    c() {
      div = element("div");
      t = text(ctx[0]);
      attr(div, "class", "font-bold z-index-10");
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, t);
    },
    p(ctx2, dirty) {
      if (dirty & 1)
        set_data(t, ctx2[0]);
    },
    d(detaching) {
      if (detaching)
        detach(div);
    }
  };
}
function create_fragment$1(ctx) {
  let div1;
  let t;
  let div0;
  let current;
  let if_block = ctx[0] && create_if_block$1(ctx);
  const default_slot_template = ctx[2].default;
  const default_slot = create_slot(default_slot_template, ctx, ctx[1], null);
  return {
    c() {
      div1 = element("div");
      if (if_block)
        if_block.c();
      t = space();
      div0 = element("div");
      if (default_slot)
        default_slot.c();
      attr(div0, "class", "flex flex-col space-y-2 flex-1 justify-center");
      attr(div1, "class", "flex flex-col space-y-2 flex-1 justify-center");
    },
    m(target, anchor) {
      insert(target, div1, anchor);
      if (if_block)
        if_block.m(div1, null);
      append(div1, t);
      append(div1, div0);
      if (default_slot) {
        default_slot.m(div0, null);
      }
      current = true;
    },
    p(ctx2, [dirty]) {
      if (ctx2[0]) {
        if (if_block) {
          if_block.p(ctx2, dirty);
        } else {
          if_block = create_if_block$1(ctx2);
          if_block.c();
          if_block.m(div1, t);
        }
      } else if (if_block) {
        if_block.d(1);
        if_block = null;
      }
      if (default_slot) {
        if (default_slot.p && (!current || dirty & 2)) {
          update_slot_base(default_slot, default_slot_template, ctx2, ctx2[1], !current ? get_all_dirty_from_scope(ctx2[1]) : get_slot_changes(default_slot_template, ctx2[1], dirty, null), null);
        }
      }
    },
    i(local) {
      if (current)
        return;
      transition_in(default_slot, local);
      current = true;
    },
    o(local) {
      transition_out(default_slot, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        detach(div1);
      if (if_block)
        if_block.d();
      if (default_slot)
        default_slot.d(detaching);
    }
  };
}
function instance$1($$self, $$props, $$invalidate) {
  let { $$slots: slots = {}, $$scope } = $$props;
  let { label = "" } = $$props;
  $$self.$$set = ($$props2) => {
    if ("label" in $$props2)
      $$invalidate(0, label = $$props2.label);
    if ("$$scope" in $$props2)
      $$invalidate(1, $$scope = $$props2.$$scope);
  };
  return [label, $$scope, slots];
}
class Field extends SvelteComponent {
  constructor(options) {
    super();
    init(this, options, instance$1, create_fragment$1, safe_not_equal, { label: 0 });
  }
}
function create_else_block_1(ctx) {
  let a;
  return {
    c() {
      a = element("a");
      a.textContent = `${ctx[4]("Login")}/${ctx[4]("register")}`;
      attr(a, "href", "/login");
    },
    m(target, anchor) {
      insert(target, a, anchor);
    },
    p: noop,
    d(detaching) {
      if (detaching)
        detach(a);
    }
  };
}
function create_if_block_3(ctx) {
  let div;
  let img;
  let img_src_value;
  let mounted;
  let dispose;
  return {
    c() {
      div = element("div");
      img = element("img");
      if (!src_url_equal(img.src, img_src_value = ctx[2].avatarUrl))
        attr(img, "src", img_src_value);
      attr(img, "alt", "Avatar");
      attr(img, "class", "w-32px h-32px rounded-full shadow-lg object-cover");
      attr(div, "class", "flex flex-row space-x-2 cursor-pointer items-center");
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, img);
      if (!mounted) {
        dispose = listen(div, "click", ctx[6]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 4 && !src_url_equal(img.src, img_src_value = ctx2[2].avatarUrl)) {
        attr(img, "src", img_src_value);
      }
    },
    d(detaching) {
      if (detaching)
        detach(div);
      mounted = false;
      dispose();
    }
  };
}
function create_if_block(ctx) {
  let div17;
  let div0;
  let t0;
  let div16;
  let div4;
  let div2;
  let img;
  let img_src_value;
  let t1;
  let div1;
  let t2_value = ctx[0].display_name + "";
  let t2;
  let t3;
  let div3;
  let t4;
  let svg;
  let path;
  let line0;
  let line1;
  let t5;
  let div10;
  let div9;
  let div5;
  let field0;
  let t6;
  let field1;
  let t7;
  let div6;
  let field2;
  let t8;
  let field3;
  let t9;
  let field4;
  let t10;
  let details;
  let summary;
  let t12;
  let div8;
  let field5;
  let t13;
  let div7;
  let field6;
  let t14;
  let field7;
  let t15;
  let div15;
  let t16;
  let div14;
  let div11;
  let t18;
  let div12;
  let t19;
  let div13;
  let div17_intro;
  let current;
  let mounted;
  let dispose;
  field0 = new Field({
    props: {
      label: ctx[4]("Name"),
      $$slots: { default: [create_default_slot_7] },
      $$scope: { ctx }
    }
  });
  field1 = new Field({
    props: {
      label: ctx[4]("Email"),
      $$slots: { default: [create_default_slot_6] },
      $$scope: { ctx }
    }
  });
  field2 = new Field({
    props: {
      label: ctx[4]("Website"),
      $$slots: { default: [create_default_slot_5] },
      $$scope: { ctx }
    }
  });
  field3 = new Field({
    props: {
      label: ctx[4]("Select image"),
      $$slots: { default: [create_default_slot_4] },
      $$scope: { ctx }
    }
  });
  field4 = new Field({
    props: {
      label: ctx[4]("About me"),
      $$slots: { default: [create_default_slot_3] },
      $$scope: { ctx }
    }
  });
  field5 = new Field({
    props: {
      label: ctx[4]("Current password"),
      $$slots: { default: [create_default_slot_2] },
      $$scope: { ctx }
    }
  });
  field6 = new Field({
    props: {
      label: ctx[4]("New password"),
      $$slots: { default: [create_default_slot_1] },
      $$scope: { ctx }
    }
  });
  field7 = new Field({
    props: {
      label: ctx[4]("Confirm password"),
      $$slots: { default: [create_default_slot] },
      $$scope: { ctx }
    }
  });
  let if_block = ctx[3] && create_if_block_1(ctx);
  return {
    c() {
      div17 = element("div");
      div0 = element("div");
      t0 = space();
      div16 = element("div");
      div4 = element("div");
      div2 = element("div");
      img = element("img");
      t1 = space();
      div1 = element("div");
      t2 = text(t2_value);
      t3 = space();
      div3 = element("div");
      t4 = space();
      svg = svg_element("svg");
      path = svg_element("path");
      line0 = svg_element("line");
      line1 = svg_element("line");
      t5 = space();
      div10 = element("div");
      div9 = element("div");
      div5 = element("div");
      create_component(field0.$$.fragment);
      t6 = space();
      create_component(field1.$$.fragment);
      t7 = space();
      div6 = element("div");
      create_component(field2.$$.fragment);
      t8 = space();
      create_component(field3.$$.fragment);
      t9 = space();
      create_component(field4.$$.fragment);
      t10 = space();
      details = element("details");
      summary = element("summary");
      summary.textContent = `${ctx[4]("Security")}`;
      t12 = space();
      div8 = element("div");
      create_component(field5.$$.fragment);
      t13 = space();
      div7 = element("div");
      create_component(field6.$$.fragment);
      t14 = space();
      create_component(field7.$$.fragment);
      t15 = space();
      div15 = element("div");
      if (if_block)
        if_block.c();
      t16 = space();
      div14 = element("div");
      div11 = element("div");
      div11.textContent = `${ctx[4]("Cancel")}`;
      t18 = space();
      div12 = element("div");
      t19 = space();
      div13 = element("div");
      div13.textContent = `${ctx[4]("Save")}`;
      attr(div0, "class", "absolute top-0 left-0 bg-black opacity-90 w-full h-full");
      if (!src_url_equal(img.src, img_src_value = ctx[2].avatarUrl))
        attr(img, "src", img_src_value);
      attr(img, "alt", "img");
      attr(img, "class", "w-32px h-32px rounded-full items-center shadow-lg object-cover");
      attr(div2, "class", "flex flex-row space-x-2 items-center");
      attr(div3, "class", "flex-1");
      attr(path, "stroke", "none");
      attr(path, "d", "M0 0h24v24H0z");
      attr(path, "fill", "none");
      attr(line0, "x1", "18");
      attr(line0, "y1", "6");
      attr(line0, "x2", "6");
      attr(line0, "y2", "18");
      attr(line1, "x1", "6");
      attr(line1, "y1", "6");
      attr(line1, "x2", "18");
      attr(line1, "y2", "18");
      attr(svg, "xmlns", "http://www.w3.org/2000/svg");
      attr(svg, "width", "24");
      attr(svg, "height", "24");
      attr(svg, "viewBox", "0 0 24 24");
      attr(svg, "stroke-width", "2");
      attr(svg, "stroke", "currentColor");
      attr(svg, "fill", "none");
      attr(svg, "stroke-linecap", "round");
      attr(svg, "stroke-linejoin", "round");
      attr(div4, "class", "flex flex-row py-5 px-2 bg-cool-gray-100 relative");
      attr(div5, "class", "flex flex-col md:flex-row md:space-x-5");
      attr(div6, "class", "flex flex-col md:flex-row md:space-x-5");
      attr(summary, "class", "font-bold");
      attr(div7, "class", "flex flex-row space-x-5");
      attr(div8, "class", "space-y-3 mt-5");
      attr(div9, "class", "space-y-5 flex flex-col bg-white p-3");
      attr(div10, "class", "flex-1 p-2 container mx-auto flex overflow-auto");
      attr(div11, "class", "cursor-pointer");
      attr(div12, "class", "flex-1");
      attr(div13, "class", "transition-all bg-blue-500 text-white p-2 rounded shadow-lg inline-block w-35 text-center cursor-pointer");
      attr(div14, "class", "flex flex-row space-x-2 font-bold items-center");
      attr(div15, "class", "py-3 px-2 bg-cool-gray-100");
      attr(div16, "class", "rounded-lg w-auto max-h-4/5 bg-white overflow-hidden shadow-md flex flex-col z-index-10");
      attr(div17, "class", "flex justify-center items-center fixed top-0 left-0 w-full h-full z-index-20");
    },
    m(target, anchor) {
      insert(target, div17, anchor);
      append(div17, div0);
      append(div17, t0);
      append(div17, div16);
      append(div16, div4);
      append(div4, div2);
      append(div2, img);
      append(div2, t1);
      append(div2, div1);
      append(div1, t2);
      append(div4, t3);
      append(div4, div3);
      append(div4, t4);
      append(div4, svg);
      append(svg, path);
      append(svg, line0);
      append(svg, line1);
      append(div16, t5);
      append(div16, div10);
      append(div10, div9);
      append(div9, div5);
      mount_component(field0, div5, null);
      append(div5, t6);
      mount_component(field1, div5, null);
      append(div9, t7);
      append(div9, div6);
      mount_component(field2, div6, null);
      append(div6, t8);
      mount_component(field3, div6, null);
      append(div9, t9);
      mount_component(field4, div9, null);
      append(div9, t10);
      append(div9, details);
      append(details, summary);
      append(details, t12);
      append(details, div8);
      mount_component(field5, div8, null);
      append(div8, t13);
      append(div8, div7);
      mount_component(field6, div7, null);
      append(div7, t14);
      mount_component(field7, div7, null);
      append(div16, t15);
      append(div16, div15);
      if (if_block)
        if_block.m(div15, null);
      append(div15, t16);
      append(div15, div14);
      append(div14, div11);
      append(div14, t18);
      append(div14, div12);
      append(div14, t19);
      append(div14, div13);
      current = true;
      if (!mounted) {
        dispose = [
          listen(svg, "click", ctx[7]),
          listen(div11, "click", ctx[16]),
          listen(div13, "click", ctx[17])
        ];
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (!current || dirty & 4 && !src_url_equal(img.src, img_src_value = ctx2[2].avatarUrl)) {
        attr(img, "src", img_src_value);
      }
      if ((!current || dirty & 1) && t2_value !== (t2_value = ctx2[0].display_name + ""))
        set_data(t2, t2_value);
      const field0_changes = {};
      if (dirty & 4194308) {
        field0_changes.$$scope = { dirty, ctx: ctx2 };
      }
      field0.$set(field0_changes);
      const field1_changes = {};
      if (dirty & 4194308) {
        field1_changes.$$scope = { dirty, ctx: ctx2 };
      }
      field1.$set(field1_changes);
      const field2_changes = {};
      if (dirty & 4194308) {
        field2_changes.$$scope = { dirty, ctx: ctx2 };
      }
      field2.$set(field2_changes);
      const field3_changes = {};
      if (dirty & 4194308) {
        field3_changes.$$scope = { dirty, ctx: ctx2 };
      }
      field3.$set(field3_changes);
      const field4_changes = {};
      if (dirty & 4194308) {
        field4_changes.$$scope = { dirty, ctx: ctx2 };
      }
      field4.$set(field4_changes);
      const field5_changes = {};
      if (dirty & 4194308) {
        field5_changes.$$scope = { dirty, ctx: ctx2 };
      }
      field5.$set(field5_changes);
      const field6_changes = {};
      if (dirty & 4194308) {
        field6_changes.$$scope = { dirty, ctx: ctx2 };
      }
      field6.$set(field6_changes);
      const field7_changes = {};
      if (dirty & 4194308) {
        field7_changes.$$scope = { dirty, ctx: ctx2 };
      }
      field7.$set(field7_changes);
      if (ctx2[3]) {
        if (if_block) {
          if_block.p(ctx2, dirty);
        } else {
          if_block = create_if_block_1(ctx2);
          if_block.c();
          if_block.m(div15, t16);
        }
      } else if (if_block) {
        if_block.d(1);
        if_block = null;
      }
    },
    i(local) {
      if (current)
        return;
      transition_in(field0.$$.fragment, local);
      transition_in(field1.$$.fragment, local);
      transition_in(field2.$$.fragment, local);
      transition_in(field3.$$.fragment, local);
      transition_in(field4.$$.fragment, local);
      transition_in(field5.$$.fragment, local);
      transition_in(field6.$$.fragment, local);
      transition_in(field7.$$.fragment, local);
      if (!div17_intro) {
        add_render_callback(() => {
          div17_intro = create_in_transition(div17, fly, { y: -10 });
          div17_intro.start();
        });
      }
      current = true;
    },
    o(local) {
      transition_out(field0.$$.fragment, local);
      transition_out(field1.$$.fragment, local);
      transition_out(field2.$$.fragment, local);
      transition_out(field3.$$.fragment, local);
      transition_out(field4.$$.fragment, local);
      transition_out(field5.$$.fragment, local);
      transition_out(field6.$$.fragment, local);
      transition_out(field7.$$.fragment, local);
      current = false;
    },
    d(detaching) {
      if (detaching)
        detach(div17);
      destroy_component(field0);
      destroy_component(field1);
      destroy_component(field2);
      destroy_component(field3);
      destroy_component(field4);
      destroy_component(field5);
      destroy_component(field6);
      destroy_component(field7);
      if (if_block)
        if_block.d();
      mounted = false;
      run_all(dispose);
    }
  };
}
function create_default_slot_7(ctx) {
  let input;
  let mounted;
  let dispose;
  return {
    c() {
      input = element("input");
      attr(input, "type", "text");
      attr(input, "class", "appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg");
    },
    m(target, anchor) {
      insert(target, input, anchor);
      set_input_value(input, ctx[2].name);
      if (!mounted) {
        dispose = listen(input, "input", ctx[8]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 4 && input.value !== ctx2[2].name) {
        set_input_value(input, ctx2[2].name);
      }
    },
    d(detaching) {
      if (detaching)
        detach(input);
      mounted = false;
      dispose();
    }
  };
}
function create_default_slot_6(ctx) {
  let input;
  let mounted;
  let dispose;
  return {
    c() {
      input = element("input");
      attr(input, "type", "email");
      attr(input, "class", "appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg");
    },
    m(target, anchor) {
      insert(target, input, anchor);
      set_input_value(input, ctx[2].email);
      if (!mounted) {
        dispose = listen(input, "input", ctx[9]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 4 && input.value !== ctx2[2].email) {
        set_input_value(input, ctx2[2].email);
      }
    },
    d(detaching) {
      if (detaching)
        detach(input);
      mounted = false;
      dispose();
    }
  };
}
function create_default_slot_5(ctx) {
  let input;
  let mounted;
  let dispose;
  return {
    c() {
      input = element("input");
      attr(input, "type", "text");
      attr(input, "class", "appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg");
    },
    m(target, anchor) {
      insert(target, input, anchor);
      set_input_value(input, ctx[2].website);
      if (!mounted) {
        dispose = listen(input, "input", ctx[10]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 4 && input.value !== ctx2[2].website) {
        set_input_value(input, ctx2[2].website);
      }
    },
    d(detaching) {
      if (detaching)
        detach(input);
      mounted = false;
      dispose();
    }
  };
}
function create_default_slot_4(ctx) {
  let label;
  let input;
  let t0;
  let t1_value = (ctx[2].file && ctx[2].file.name || ctx[4]("Select image")) + "";
  let t1;
  let mounted;
  let dispose;
  return {
    c() {
      label = element("label");
      input = element("input");
      t0 = space();
      t1 = text(t1_value);
      attr(input, "type", "file");
      attr(input, "class", "hidden");
      attr(input, "accept", "image/png,image/jpeg");
      attr(label, "class", "p-2 rounded ring-2 ring-gray-100 cursor-pointer hover:ring-blue-500 hover:shadow-lg");
    },
    m(target, anchor) {
      insert(target, label, anchor);
      append(label, input);
      append(label, t0);
      append(label, t1);
      if (!mounted) {
        dispose = listen(input, "change", ctx[11]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 4 && t1_value !== (t1_value = (ctx2[2].file && ctx2[2].file.name || ctx2[4]("Select image")) + ""))
        set_data(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        detach(label);
      mounted = false;
      dispose();
    }
  };
}
function create_default_slot_3(ctx) {
  let textarea;
  let mounted;
  let dispose;
  return {
    c() {
      textarea = element("textarea");
      attr(textarea, "class", "appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg");
    },
    m(target, anchor) {
      insert(target, textarea, anchor);
      set_input_value(textarea, ctx[2].description);
      if (!mounted) {
        dispose = listen(textarea, "input", ctx[12]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 4) {
        set_input_value(textarea, ctx2[2].description);
      }
    },
    d(detaching) {
      if (detaching)
        detach(textarea);
      mounted = false;
      dispose();
    }
  };
}
function create_default_slot_2(ctx) {
  let input;
  let mounted;
  let dispose;
  return {
    c() {
      input = element("input");
      attr(input, "type", "password");
      attr(input, "class", "appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg");
    },
    m(target, anchor) {
      insert(target, input, anchor);
      set_input_value(input, ctx[2].current_pwd);
      if (!mounted) {
        dispose = listen(input, "input", ctx[13]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 4 && input.value !== ctx2[2].current_pwd) {
        set_input_value(input, ctx2[2].current_pwd);
      }
    },
    d(detaching) {
      if (detaching)
        detach(input);
      mounted = false;
      dispose();
    }
  };
}
function create_default_slot_1(ctx) {
  let input;
  let mounted;
  let dispose;
  return {
    c() {
      input = element("input");
      attr(input, "type", "password");
      attr(input, "class", "appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg");
    },
    m(target, anchor) {
      insert(target, input, anchor);
      set_input_value(input, ctx[2].new_password);
      if (!mounted) {
        dispose = listen(input, "input", ctx[14]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 4 && input.value !== ctx2[2].new_password) {
        set_input_value(input, ctx2[2].new_password);
      }
    },
    d(detaching) {
      if (detaching)
        detach(input);
      mounted = false;
      dispose();
    }
  };
}
function create_default_slot(ctx) {
  let input;
  let mounted;
  let dispose;
  return {
    c() {
      input = element("input");
      attr(input, "type", "password");
      attr(input, "class", "appearance-none p-2 rounded ring-2 ring-gray-100 focus:ring-blue-500 focus:shadow-lg");
    },
    m(target, anchor) {
      insert(target, input, anchor);
      set_input_value(input, ctx[2].confirm_password);
      if (!mounted) {
        dispose = listen(input, "input", ctx[15]);
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty & 4 && input.value !== ctx2[2].confirm_password) {
        set_input_value(input, ctx2[2].confirm_password);
      }
    },
    d(detaching) {
      if (detaching)
        detach(input);
      mounted = false;
      dispose();
    }
  };
}
function create_if_block_1(ctx) {
  let if_block_anchor;
  function select_block_type_1(ctx2, dirty) {
    if (ctx2[3].validationType === "success")
      return create_if_block_2;
    return create_else_block;
  }
  let current_block_type = select_block_type_1(ctx);
  let if_block = current_block_type(ctx);
  return {
    c() {
      if_block.c();
      if_block_anchor = empty();
    },
    m(target, anchor) {
      if_block.m(target, anchor);
      insert(target, if_block_anchor, anchor);
    },
    p(ctx2, dirty) {
      if (current_block_type === (current_block_type = select_block_type_1(ctx2)) && if_block) {
        if_block.p(ctx2, dirty);
      } else {
        if_block.d(1);
        if_block = current_block_type(ctx2);
        if (if_block) {
          if_block.c();
          if_block.m(if_block_anchor.parentNode, if_block_anchor);
        }
      }
    },
    d(detaching) {
      if_block.d(detaching);
      if (detaching)
        detach(if_block_anchor);
    }
  };
}
function create_else_block(ctx) {
  let div;
  let t_value = ctx[3].validationMessage + "";
  let t;
  return {
    c() {
      div = element("div");
      t = text(t_value);
      attr(div, "class", "border-2 border-red-300 rounded-full p-2 my-2 bg-white");
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, t);
    },
    p(ctx2, dirty) {
      if (dirty & 8 && t_value !== (t_value = ctx2[3].validationMessage + ""))
        set_data(t, t_value);
    },
    d(detaching) {
      if (detaching)
        detach(div);
    }
  };
}
function create_if_block_2(ctx) {
  let div;
  let t_value = ctx[3].validationMessage + "";
  let t;
  return {
    c() {
      div = element("div");
      t = text(t_value);
      attr(div, "class", "border-2 border-green-300 rounded-full p-2 my-2 bg-white");
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, t);
    },
    p(ctx2, dirty) {
      if (dirty & 8 && t_value !== (t_value = ctx2[3].validationMessage + ""))
        set_data(t, t_value);
    },
    d(detaching) {
      if (detaching)
        detach(div);
    }
  };
}
function create_fragment(ctx) {
  let t;
  let if_block1_anchor;
  let current;
  function select_block_type(ctx2, dirty) {
    if (ctx2[0].isLogged)
      return create_if_block_3;
    return create_else_block_1;
  }
  let current_block_type = select_block_type(ctx);
  let if_block0 = current_block_type(ctx);
  let if_block1 = ctx[1] && create_if_block(ctx);
  return {
    c() {
      if_block0.c();
      t = space();
      if (if_block1)
        if_block1.c();
      if_block1_anchor = empty();
    },
    m(target, anchor) {
      if_block0.m(target, anchor);
      insert(target, t, anchor);
      if (if_block1)
        if_block1.m(target, anchor);
      insert(target, if_block1_anchor, anchor);
      current = true;
    },
    p(ctx2, [dirty]) {
      if (current_block_type === (current_block_type = select_block_type(ctx2)) && if_block0) {
        if_block0.p(ctx2, dirty);
      } else {
        if_block0.d(1);
        if_block0 = current_block_type(ctx2);
        if (if_block0) {
          if_block0.c();
          if_block0.m(t.parentNode, t);
        }
      }
      if (ctx2[1]) {
        if (if_block1) {
          if_block1.p(ctx2, dirty);
          if (dirty & 2) {
            transition_in(if_block1, 1);
          }
        } else {
          if_block1 = create_if_block(ctx2);
          if_block1.c();
          transition_in(if_block1, 1);
          if_block1.m(if_block1_anchor.parentNode, if_block1_anchor);
        }
      } else if (if_block1) {
        group_outros();
        transition_out(if_block1, 1, 1, () => {
          if_block1 = null;
        });
        check_outros();
      }
    },
    i(local) {
      if (current)
        return;
      transition_in(if_block1);
      current = true;
    },
    o(local) {
      transition_out(if_block1);
      current = false;
    },
    d(detaching) {
      if_block0.d(detaching);
      if (detaching)
        detach(t);
      if (if_block1)
        if_block1.d(detaching);
      if (detaching)
        detach(if_block1_anchor);
    }
  };
}
function instance($$self, $$props, $$invalidate) {
  let { config = {
    isLogged: true,
    url: "https://google.com.uy",
    user_avatar_url: "",
    display_name: "Joaquin",
    user_email: "joaquinvelazquezcamacho@gmail.com",
    user_url: "https://google.com.uy",
    description: "",
    login_url: "",
    i18n: {}
  } } = $$props;
  const __ = doGetter(config.i18n || {});
  let show = false;
  let profile = {
    name: config.display_name,
    email: config.user_email,
    website: config.user_url,
    avatarUrl: config.user_avatar_url,
    description: config.description,
    file: void 0,
    current_pwd: void 0,
    new_password: void 0,
    confirm_password: void 0
  };
  let isUnlocked = true;
  let validation = null;
  function submitProfile() {
    if (isUnlocked) {
      isUnlocked = false;
      if (isEmpty(profile.name)) {
        return;
      }
      if (isEmpty(profile.email)) {
        isUnlocked = true;
        return;
      }
      const formData = new FormData();
      if (nonEmpty(profile.current_pwd) || nonEmpty(profile.new_password) || nonEmpty(profile.confirm_password)) {
        if (isEmpty(profile.current_pwd)) {
          return;
        }
        if (isEmpty(profile.new_password) || isEmpty(profile.confirm_password) || profile.new_password !== profile.confirm_password) {
          return;
        }
        formData.append("current_pwd", profile.current_pwd);
        formData.append("new_password", profile.new_password);
        formData.append("confirm_password", profile.confirm_password);
      }
      if (profile.file)
        formData.append("file", profile.file);
      profile.name && formData.append("display_name", profile.name);
      profile.email && formData.append("user_email", profile.email);
      profile.website && formData.append("user_url", profile.website);
      profile.description && formData.append("description", profile.description);
      fetch("/wp-json/mcplugin/v1/user", {
        method: "POST",
        headers: {
          "accept": "application/json",
          "enctype": "multipart/form-data",
          "X-WP-Nonce": MCApp.nonce
        },
        body: formData
      }).then((response) => response.json()).then((response) => {
        $$invalidate(3, validation = {});
        const ud = response.userData;
        $$invalidate(2, profile.name = ud.display_name || profile.name, profile);
        $$invalidate(2, profile.email = ud.user_email || profile.email, profile);
        $$invalidate(2, profile.website = ud.user_url || profile.website, profile);
        $$invalidate(2, profile.description = ud.description || profile.description, profile);
        if (ud.user_avatar_url) {
          $$invalidate(2, profile.avatarUrl = ud.user_avatar_url, profile);
        }
        isUnlocked = true;
        $$invalidate(3, validation.validationType = "success", validation);
        $$invalidate(3, validation.validationMessage = "Changes saved", validation);
      }).catch((e) => {
        console.log(e);
        isUnlocked = true;
        $$invalidate(3, validation.validationType = "error", validation);
        $$invalidate(3, validation.validationMessage = "Can\xB4t save changes", validation);
      }).finally(() => {
        setTimeout(() => $$invalidate(3, validation = null), 3e3);
      });
    }
  }
  const click_handler = () => $$invalidate(1, show = true);
  const click_handler_1 = () => $$invalidate(1, show = false);
  function input_input_handler() {
    profile.name = this.value;
    $$invalidate(2, profile);
  }
  function input_input_handler_1() {
    profile.email = this.value;
    $$invalidate(2, profile);
  }
  function input_input_handler_2() {
    profile.website = this.value;
    $$invalidate(2, profile);
  }
  const change_handler = (e) => {
    $$invalidate(2, profile.file = e.target.files[0], profile);
    console.log(profile);
  };
  function textarea_input_handler() {
    profile.description = this.value;
    $$invalidate(2, profile);
  }
  function input_input_handler_3() {
    profile.current_pwd = this.value;
    $$invalidate(2, profile);
  }
  function input_input_handler_4() {
    profile.new_password = this.value;
    $$invalidate(2, profile);
  }
  function input_input_handler_5() {
    profile.confirm_password = this.value;
    $$invalidate(2, profile);
  }
  const click_handler_2 = () => $$invalidate(1, show = false);
  const click_handler_3 = () => submitProfile();
  $$self.$$set = ($$props2) => {
    if ("config" in $$props2)
      $$invalidate(0, config = $$props2.config);
  };
  return [
    config,
    show,
    profile,
    validation,
    __,
    submitProfile,
    click_handler,
    click_handler_1,
    input_input_handler,
    input_input_handler_1,
    input_input_handler_2,
    change_handler,
    textarea_input_handler,
    input_input_handler_3,
    input_input_handler_4,
    input_input_handler_5,
    click_handler_2,
    click_handler_3
  ];
}
class ProfileViewer extends SvelteComponent {
  constructor(options) {
    super();
    init(this, options, instance, create_fragment, safe_not_equal, { config: 0 });
  }
}
function applySelection(selector, handler) {
  Array.from(document.getElementsByClassName(selector)).forEach((elem) => handler(elem));
}
function renderUserPermissions(selector, config) {
  applySelection(selector, (elem) => new UserPermissions({ target: elem, props: { config } }));
}
function renderPermissionEditor(selector, config) {
  applySelection(selector, (elem) => new PermissionEditor({ target: elem, props: { config } }));
}
function renderMetaPost(selector, config) {
  applySelection(selector, (elem) => new MetaPost({ target: elem, props: { config } }));
}
function renderProfile(selector, config) {
  applySelection(selector, (elem) => new ProfileViewer({ target: elem, props: { config } }));
}
export { renderMetaPost, renderPermissionEditor, renderProfile, renderUserPermissions };
