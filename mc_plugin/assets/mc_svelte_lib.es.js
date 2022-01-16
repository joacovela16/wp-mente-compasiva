var __defProp = Object.defineProperty;
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
var windi = "";
function noop() {
}
function run(fn) {
  return fn();
}
function blank_object() {
  return Object.create(null);
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
function is_empty(obj) {
  return Object.keys(obj).length === 0;
}
function append(target, node) {
  target.appendChild(node);
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
function listen(node, event, handler, options) {
  node.addEventListener(event, handler, options);
  return () => node.removeEventListener(event, handler, options);
}
function attr(node, attribute, value) {
  if (value == null)
    node.removeAttribute(attribute);
  else if (node.getAttribute(attribute) !== value)
    node.setAttribute(attribute, value);
}
function get_binding_group_value(group, __value, checked) {
  const value = new Set();
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
function toggle_class(element2, name, toggle) {
  element2.classList[toggle ? "add" : "remove"](name);
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
const seen_callbacks = new Set();
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
const outroing = new Set();
function transition_in(block, local) {
  if (block && block.i) {
    outroing.delete(block);
    block.i(local);
  }
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
  const new_lookup = new Map();
  const deltas = new Map();
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
  const will_move = new Set();
  const did_move = new Set();
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
function get_each_context_3$1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[11] = list[i];
  child_ctx[13] = i;
  return child_ctx;
}
function create_each_block_3$1(key_1, ctx) {
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
      attr(div, "class", div_class_value = "shadow font-bold hover:bg-blue-500 hover:text-white cursor-pointer rounded flex-1 p-2 " + (ctx[2].name === ctx[11].name && "bg-blue-500 text-white"));
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
      if (dirty & 4 && div_class_value !== (div_class_value = "shadow font-bold hover:bg-blue-500 hover:text-white cursor-pointer rounded flex-1 p-2 " + (ctx[2].name === ctx[11].name && "bg-blue-500 text-white"))) {
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
      attr(input, "name", input_name_value = "" + (ctx[0].names.permission + "[" + ctx[11].name + "][post_types][]"));
      ctx[8][1][ctx[13]].push(input);
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, label);
      append(label, input);
      input.checked = ~ctx[1][ctx[11].name].post_types.indexOf(input.__value);
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
      if (dirty & 1 && input_name_value !== (input_name_value = "" + (ctx[0].names.permission + "[" + ctx[11].name + "][post_types][]"))) {
        attr(input, "name", input_name_value);
      }
      if (dirty & 10) {
        input.checked = ~ctx[1][ctx[11].name].post_types.indexOf(input.__value);
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
      attr(input, "name", input_name_value = "" + (ctx[0].names.permission + "[" + ctx[11].name + "][capabilities][]"));
      ctx[8][0][ctx[13]].push(input);
    },
    m(target, anchor) {
      insert(target, div, anchor);
      append(div, label);
      append(label, input);
      input.checked = ~ctx[1][ctx[11].name].capabilities.indexOf(input.__value);
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
      if (dirty & 1 && input_name_value !== (input_name_value = "" + (ctx[0].names.permission + "[" + ctx[11].name + "][capabilities][]"))) {
        attr(input, "name", input_name_value);
      }
      if (dirty & 10) {
        input.checked = ~ctx[1][ctx[11].name].capabilities.indexOf(input.__value);
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
      input.value = ctx[11].name;
      attr(input, "name", input_name_value = "" + (ctx[0].names.permission + "[" + ctx[11].name + "][name]"));
      attr(div1, "class", "flex-1");
      attr(div3, "class", "flex-1");
      attr(div4, "class", "p-2 flex flex-row");
      toggle_class(div4, "hidden", ctx[11].name !== ctx[2].name);
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
      if (dirty & 1 && input_name_value !== (input_name_value = "" + (ctx[0].names.permission + "[" + ctx[11].name + "][name]"))) {
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
        toggle_class(div4, "hidden", ctx[11].name !== ctx[2].name);
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
function create_fragment$2(ctx) {
  let div1;
  let div0;
  let each_blocks_1 = [];
  let each0_lookup = new Map();
  let t;
  let each_blocks = [];
  let each1_lookup = new Map();
  let each_value_3 = ctx[3];
  const get_key = (ctx2) => ctx2[11].name;
  for (let i = 0; i < each_value_3.length; i += 1) {
    let child_ctx = get_each_context_3$1(ctx, each_value_3, i);
    let key = get_key(child_ctx);
    each0_lookup.set(key, each_blocks_1[i] = create_each_block_3$1(key, child_ctx));
  }
  let each_value = ctx[3];
  const get_key_1 = (ctx2) => ctx2[11].name;
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
        each_blocks_1 = update_keyed_each(each_blocks_1, dirty, get_key, 1, ctx2, each_value_3, each0_lookup, div0, destroy_block, create_each_block_3$1, null, get_each_context_3$1);
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
function instance$2($$self, $$props, $$invalidate) {
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
  console.log(config);
  const finalConfig = __spreadValues(__spreadValues({}, defaultConfig), config || {});
  if (!finalConfig.selections) {
    finalConfig.selections = {};
  }
  doDefault(finalConfig.permissions, finalConfig.selections || {}, (x) => x.name, (src) => ({
    name: src.name,
    post_types: [],
    capabilities: []
  }));
  Object.keys(finalConfig.selections || {}).forEach((x) => {
    const item = finalConfig.selections[x];
    item.post_types = item.post_types || [];
    item.capabilities = item.capabilities || [];
  });
  const permissions = finalConfig.permissions;
  const selections = finalConfig.selections;
  const __ = doGetter(finalConfig.i18n || {});
  let currentTab = permissions[0];
  const $$binding_groups = [[], []];
  const click_handler = (index) => $$invalidate(2, currentTab = permissions[index]);
  function input_change_handler(item, index) {
    selections[item.name].post_types = get_binding_group_value($$binding_groups[1][index], this.__value, this.checked);
    $$invalidate(1, selections);
  }
  function input_change_handler_1(item, index) {
    selections[item.name].capabilities = get_binding_group_value($$binding_groups[0][index], this.__value, this.checked);
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
    init(this, options, instance$2, create_fragment$2, safe_not_equal, { config: 5 });
  }
}
function get_each_context$1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[20] = list[i];
  child_ctx[21] = list;
  child_ctx[22] = i;
  return child_ctx;
}
function get_each_context_1$1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[23] = list[i];
  return child_ctx;
}
function get_each_context_2$1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[23] = list[i];
  return child_ctx;
}
function get_each_context_3(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[28] = list[i];
  child_ctx[30] = i;
  return child_ctx;
}
function get_each_context_4(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[23] = list[i];
  return child_ctx;
}
function create_each_block_4(ctx) {
  let label;
  let input;
  let input_checked_value;
  let t0;
  let span;
  let t1_value = ctx[23] + "";
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
      input.value = ctx[23];
      attr(input, "name", "permissions[" + ctx[22] + "][post_types][]");
      input.checked = input_checked_value = ctx[20].post_types.includes(ctx[23]);
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
      if (dirty[0] & 1 && input_checked_value !== (input_checked_value = ctx2[20].post_types.includes(ctx2[23]))) {
        input.checked = input_checked_value;
      }
    },
    d(detaching) {
      if (detaching)
        detach(label);
    }
  };
}
function create_each_block_3(ctx) {
  let div1;
  let input;
  let input_value_value;
  let t0;
  let div0;
  let span0;
  let t1_value = ctx[28] + "";
  let t1;
  let t2;
  let span1;
  let t3;
  let mounted;
  let dispose;
  function click_handler_2() {
    return ctx[13](ctx[20], ctx[30], ctx[21], ctx[22]);
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
      span1.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`;
      t3 = space();
      attr(input, "type", "hidden");
      input.value = input_value_value = ctx[28];
      attr(input, "name", "permissions[" + ctx[22] + "][capabilities][]");
      attr(span1, "class", "ml-2 font-bold");
      attr(div0, "class", "flex shadow items-center rounded mr-1 px-1");
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
      if (dirty[0] & 1 && input_value_value !== (input_value_value = ctx[28])) {
        input.value = input_value_value;
      }
      if (dirty[0] & 1 && t1_value !== (t1_value = ctx[28] + ""))
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
function create_each_block_2$1(ctx) {
  let label;
  let input;
  let input_value_value;
  let input_checked_value;
  let t0;
  let span;
  let t1_value = ctx[23] + "";
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
      input.value = input_value_value = ctx[23];
      attr(input, "name", "defaults[permissions][" + ctx[22] + "][post_types][]");
      input.checked = input_checked_value = ctx[0].defaults.permissions[ctx[22]].post_types.includes(ctx[23]);
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
      if (dirty[0] & 1 && input_value_value !== (input_value_value = ctx2[23])) {
        input.value = input_value_value;
      }
      if (dirty[0] & 1 && input_checked_value !== (input_checked_value = ctx2[0].defaults.permissions[ctx2[22]].post_types.includes(ctx2[23]))) {
        input.checked = input_checked_value;
      }
      if (dirty[0] & 1 && t1_value !== (t1_value = ctx2[23] + ""))
        set_data(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        detach(label);
    }
  };
}
function create_each_block_1$1(ctx) {
  let div;
  let input;
  let input_value_value;
  let input_checked_value;
  let t0;
  let span;
  let t1_value = ctx[23] + "";
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
      input.value = input_value_value = ctx[23];
      attr(input, "name", "defaults[permissions][" + ctx[22] + "][capabilities][]");
      input.checked = input_checked_value = ctx[0].defaults.permissions[ctx[22]].capabilities.includes(ctx[23]);
      attr(div, "class", "flex items-center");
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
      if (dirty[0] & 1 && input_value_value !== (input_value_value = ctx2[23])) {
        input.value = input_value_value;
      }
      if (dirty[0] & 1 && input_checked_value !== (input_checked_value = ctx2[0].defaults.permissions[ctx2[22]].capabilities.includes(ctx2[23]))) {
        input.checked = input_checked_value;
      }
      if (dirty[0] & 1 && t1_value !== (t1_value = ctx2[23] + ""))
        set_data(t1, t1_value);
    },
    d(detaching) {
      if (detaching)
        detach(div);
    }
  };
}
function create_if_block_1$1(ctx) {
  let div;
  let mounted;
  let dispose;
  function click_handler_3() {
    return ctx[14](ctx[22]);
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
function create_if_block$1(ctx) {
  let div;
  let mounted;
  let dispose;
  function click_handler_4() {
    return ctx[15](ctx[22]);
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
function create_each_block$1(ctx) {
  let div16;
  let div11;
  let div4;
  let p0;
  let t1;
  let div3;
  let label0;
  let t2_value = ctx[5]("Name") + "";
  let t2;
  let t3;
  let input0;
  let input0_name_value;
  let input0_value_value;
  let t4;
  let label1;
  let input1;
  let input1_name_value;
  let input1_checked_value;
  let t5;
  let span0;
  let t7;
  let div0;
  let span1;
  let t9;
  let t10;
  let div2;
  let span2;
  let t12;
  let input2;
  let t13;
  let div1;
  let t14;
  let div10;
  let p1;
  let t16;
  let div9;
  let div6;
  let p2;
  let t18;
  let div5;
  let t19;
  let div8;
  let p3;
  let t21;
  let div7;
  let t22;
  let div15;
  let div12;
  let t23;
  let t24;
  let div13;
  let t25;
  let div14;
  let t27;
  let mounted;
  let dispose;
  let each_value_4 = ctx[4];
  let each_blocks_3 = [];
  for (let i = 0; i < each_value_4.length; i += 1) {
    each_blocks_3[i] = create_each_block_4(get_each_context_4(ctx, each_value_4, i));
  }
  function keypress_handler(...args) {
    return ctx[12](ctx[22], ...args);
  }
  let each_value_3 = ctx[20].capabilities || [];
  let each_blocks_2 = [];
  for (let i = 0; i < each_value_3.length; i += 1) {
    each_blocks_2[i] = create_each_block_3(get_each_context_3(ctx, each_value_3, i));
  }
  let each_value_2 = ctx[20].post_types;
  let each_blocks_1 = [];
  for (let i = 0; i < each_value_2.length; i += 1) {
    each_blocks_1[i] = create_each_block_2$1(get_each_context_2$1(ctx, each_value_2, i));
  }
  let each_value_1 = ctx[20].capabilities || [];
  let each_blocks = [];
  for (let i = 0; i < each_value_1.length; i += 1) {
    each_blocks[i] = create_each_block_1$1(get_each_context_1$1(ctx, each_value_1, i));
  }
  let if_block0 = ctx[22] > 0 && create_if_block_1$1(ctx);
  let if_block1 = ctx[22] < ctx[0].permissions.length - 1 && create_if_block$1(ctx);
  function click_handler_5(...args) {
    return ctx[16](ctx[22], ...args);
  }
  return {
    c() {
      div16 = element("div");
      div11 = element("div");
      div4 = element("div");
      p0 = element("p");
      p0.textContent = `${ctx[5]("Configuration")}`;
      t1 = space();
      div3 = element("div");
      label0 = element("label");
      t2 = text(t2_value);
      t3 = space();
      input0 = element("input");
      t4 = space();
      label1 = element("label");
      input1 = element("input");
      t5 = space();
      span0 = element("span");
      span0.textContent = `${ctx[5]("Signed required")}`;
      t7 = space();
      div0 = element("div");
      span1 = element("span");
      span1.textContent = `${ctx[5]("Post type")}`;
      t9 = space();
      for (let i = 0; i < each_blocks_3.length; i += 1) {
        each_blocks_3[i].c();
      }
      t10 = space();
      div2 = element("div");
      span2 = element("span");
      span2.textContent = `${ctx[5]("Capabilities")}`;
      t12 = space();
      input2 = element("input");
      t13 = space();
      div1 = element("div");
      for (let i = 0; i < each_blocks_2.length; i += 1) {
        each_blocks_2[i].c();
      }
      t14 = space();
      div10 = element("div");
      p1 = element("p");
      p1.textContent = `${ctx[5]("Default user registry behavior")}`;
      t16 = space();
      div9 = element("div");
      div6 = element("div");
      p2 = element("p");
      p2.textContent = `${ctx[5]("Default posts")}`;
      t18 = space();
      div5 = element("div");
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].c();
      }
      t19 = space();
      div8 = element("div");
      p3 = element("p");
      p3.textContent = `${ctx[5]("Default capabilities")}`;
      t21 = space();
      div7 = element("div");
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      t22 = space();
      div15 = element("div");
      div12 = element("div");
      if (if_block0)
        if_block0.c();
      t23 = space();
      if (if_block1)
        if_block1.c();
      t24 = space();
      div13 = element("div");
      t25 = space();
      div14 = element("div");
      div14.textContent = `${ctx[5]("Delete")}`;
      t27 = space();
      attr(p0, "class", "font-bold");
      attr(input0, "type", "text");
      attr(input0, "name", input0_name_value = "permissions[" + ctx[22] + "][name]");
      input0.value = input0_value_value = ctx[20].name;
      attr(input0, "class", "w-auto");
      attr(label0, "class", "flex flex-col");
      attr(input1, "type", "checkbox");
      attr(input1, "name", input1_name_value = "permissions[" + ctx[22] + "][logged_required]'");
      input1.checked = input1_checked_value = ctx[20].logged_required === "on";
      attr(input2, "type", "text");
      attr(div1, "class", "flex flex-wrap");
      attr(div2, "class", "flex flex-col space-y-1 flex-1");
      attr(div3, "class", "flex flex-col space-y-1");
      attr(div4, "class", "flex-1");
      attr(p1, "class", "font-bold");
      attr(div9, "class", "flex flex-row space-x-2");
      attr(div10, "class", "flex-1 ");
      attr(div11, "class", "flex space-x-2");
      attr(div12, "class", "flex flex-col ");
      attr(div13, "class", "flex-1");
      attr(div14, "class", "p-2 bg-red-500 rounded text-white ");
      attr(div15, "class", "flex items-center ");
      attr(div16, "class", "rounded bg-white p-2");
    },
    m(target, anchor) {
      insert(target, div16, anchor);
      append(div16, div11);
      append(div11, div4);
      append(div4, p0);
      append(div4, t1);
      append(div4, div3);
      append(div3, label0);
      append(label0, t2);
      append(label0, t3);
      append(label0, input0);
      append(div3, t4);
      append(div3, label1);
      append(label1, input1);
      append(label1, t5);
      append(label1, span0);
      append(div3, t7);
      append(div3, div0);
      append(div0, span1);
      append(div0, t9);
      for (let i = 0; i < each_blocks_3.length; i += 1) {
        each_blocks_3[i].m(div0, null);
      }
      append(div3, t10);
      append(div3, div2);
      append(div2, span2);
      append(div2, t12);
      append(div2, input2);
      append(div2, t13);
      append(div2, div1);
      for (let i = 0; i < each_blocks_2.length; i += 1) {
        each_blocks_2[i].m(div1, null);
      }
      append(div11, t14);
      append(div11, div10);
      append(div10, p1);
      append(div10, t16);
      append(div10, div9);
      append(div9, div6);
      append(div6, p2);
      append(div6, t18);
      append(div6, div5);
      for (let i = 0; i < each_blocks_1.length; i += 1) {
        each_blocks_1[i].m(div5, null);
      }
      append(div9, t19);
      append(div9, div8);
      append(div8, p3);
      append(div8, t21);
      append(div8, div7);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(div7, null);
      }
      append(div16, t22);
      append(div16, div15);
      append(div15, div12);
      if (if_block0)
        if_block0.m(div12, null);
      append(div12, t23);
      if (if_block1)
        if_block1.m(div12, null);
      append(div15, t24);
      append(div15, div13);
      append(div15, t25);
      append(div15, div14);
      append(div16, t27);
      if (!mounted) {
        dispose = [
          listen(input2, "keypress", keypress_handler),
          listen(div14, "click", click_handler_5)
        ];
        mounted = true;
      }
    },
    p(new_ctx, dirty) {
      ctx = new_ctx;
      if (dirty[0] & 1 && input0_value_value !== (input0_value_value = ctx[20].name) && input0.value !== input0_value_value) {
        input0.value = input0_value_value;
      }
      if (dirty[0] & 1 && input1_checked_value !== (input1_checked_value = ctx[20].logged_required === "on")) {
        input1.checked = input1_checked_value;
      }
      if (dirty[0] & 17) {
        each_value_4 = ctx[4];
        let i;
        for (i = 0; i < each_value_4.length; i += 1) {
          const child_ctx = get_each_context_4(ctx, each_value_4, i);
          if (each_blocks_3[i]) {
            each_blocks_3[i].p(child_ctx, dirty);
          } else {
            each_blocks_3[i] = create_each_block_4(child_ctx);
            each_blocks_3[i].c();
            each_blocks_3[i].m(div0, null);
          }
        }
        for (; i < each_blocks_3.length; i += 1) {
          each_blocks_3[i].d(1);
        }
        each_blocks_3.length = each_value_4.length;
      }
      if (dirty[0] & 1) {
        each_value_3 = ctx[20].capabilities || [];
        let i;
        for (i = 0; i < each_value_3.length; i += 1) {
          const child_ctx = get_each_context_3(ctx, each_value_3, i);
          if (each_blocks_2[i]) {
            each_blocks_2[i].p(child_ctx, dirty);
          } else {
            each_blocks_2[i] = create_each_block_3(child_ctx);
            each_blocks_2[i].c();
            each_blocks_2[i].m(div1, null);
          }
        }
        for (; i < each_blocks_2.length; i += 1) {
          each_blocks_2[i].d(1);
        }
        each_blocks_2.length = each_value_3.length;
      }
      if (dirty[0] & 1) {
        each_value_2 = ctx[20].post_types;
        let i;
        for (i = 0; i < each_value_2.length; i += 1) {
          const child_ctx = get_each_context_2$1(ctx, each_value_2, i);
          if (each_blocks_1[i]) {
            each_blocks_1[i].p(child_ctx, dirty);
          } else {
            each_blocks_1[i] = create_each_block_2$1(child_ctx);
            each_blocks_1[i].c();
            each_blocks_1[i].m(div5, null);
          }
        }
        for (; i < each_blocks_1.length; i += 1) {
          each_blocks_1[i].d(1);
        }
        each_blocks_1.length = each_value_2.length;
      }
      if (dirty[0] & 1) {
        each_value_1 = ctx[20].capabilities || [];
        let i;
        for (i = 0; i < each_value_1.length; i += 1) {
          const child_ctx = get_each_context_1$1(ctx, each_value_1, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
          } else {
            each_blocks[i] = create_each_block_1$1(child_ctx);
            each_blocks[i].c();
            each_blocks[i].m(div7, null);
          }
        }
        for (; i < each_blocks.length; i += 1) {
          each_blocks[i].d(1);
        }
        each_blocks.length = each_value_1.length;
      }
      if (ctx[22] > 0)
        if_block0.p(ctx, dirty);
      if (ctx[22] < ctx[0].permissions.length - 1) {
        if (if_block1) {
          if_block1.p(ctx, dirty);
        } else {
          if_block1 = create_if_block$1(ctx);
          if_block1.c();
          if_block1.m(div12, null);
        }
      } else if (if_block1) {
        if_block1.d(1);
        if_block1 = null;
      }
    },
    d(detaching) {
      if (detaching)
        detach(div16);
      destroy_each(each_blocks_3, detaching);
      destroy_each(each_blocks_2, detaching);
      destroy_each(each_blocks_1, detaching);
      destroy_each(each_blocks, detaching);
      if (if_block0)
        if_block0.d();
      if (if_block1)
        if_block1.d();
      mounted = false;
      run_all(dispose);
    }
  };
}
function create_fragment$1(ctx) {
  let div1;
  let div0;
  let button0;
  let t1;
  let button1;
  let t3;
  let details;
  let summary;
  let t5;
  let form;
  let mounted;
  let dispose;
  let each_value = ctx[0].permissions;
  let each_blocks = [];
  for (let i = 0; i < each_value.length; i += 1) {
    each_blocks[i] = create_each_block$1(get_each_context$1(ctx, each_value, i));
  }
  return {
    c() {
      div1 = element("div");
      div0 = element("div");
      button0 = element("button");
      button0.textContent = `${ctx[5]("Save settings")}`;
      t1 = space();
      button1 = element("button");
      button1.textContent = `${ctx[5]("Add new")}`;
      t3 = space();
      details = element("details");
      summary = element("summary");
      summary.textContent = `${ctx[5]("Permissions")}`;
      t5 = space();
      form = element("form");
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].c();
      }
      attr(button0, "class", "bg-blue-500 text-white rounded p-1");
      attr(button1, "class", "border-1 border-blue-500 rounded p-1 bg-white");
      attr(div0, "class", "flex flex-row space-x-2 mt-2");
      attr(form, "action", ctx[2]);
      attr(form, "method", "post");
      attr(form, "class", "space-y-2");
      details.open = true;
      attr(div1, "class", "space-y-2");
    },
    m(target, anchor) {
      insert(target, div1, anchor);
      append(div1, div0);
      append(div0, button0);
      append(div0, t1);
      append(div0, button1);
      append(div1, t3);
      append(div1, details);
      append(details, summary);
      append(details, t5);
      append(details, form);
      for (let i = 0; i < each_blocks.length; i += 1) {
        each_blocks[i].m(form, null);
      }
      ctx[17](form);
      if (!mounted) {
        dispose = [
          listen(button0, "click", ctx[10]),
          listen(button1, "click", ctx[11])
        ];
        mounted = true;
      }
    },
    p(ctx2, dirty) {
      if (dirty[0] & 1009) {
        each_value = ctx2[0].permissions;
        let i;
        for (i = 0; i < each_value.length; i += 1) {
          const child_ctx = get_each_context$1(ctx2, each_value, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
          } else {
            each_blocks[i] = create_each_block$1(child_ctx);
            each_blocks[i].c();
            each_blocks[i].m(form, null);
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
        detach(div1);
      destroy_each(each_blocks, detaching);
      ctx[17](null);
      mounted = false;
      run_all(dispose);
    }
  };
}
function instance$1($$self, $$props, $$invalidate) {
  let { config = {
    postUrl: "",
    defaultPermission: {
      name: "New permission",
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
    defaults: { permissions: [], capabilities: [] },
    i18n: {}
  } } = $$props;
  config.defaults = config.defaults || {};
  config.defaults.permissions = config.defaults.permissions || [...config.permissions];
  const perTmp = config.defaults.permissions;
  for (let i = 0; i < config.permissions.length; i++) {
    perTmp[i] = perTmp[i] || config.permissions[i];
    perTmp[i].post_types = perTmp[i].post_types || [];
    perTmp[i].capabilities = perTmp[i].capabilities || [];
  }
  const postUrl = config.postUrl;
  const defaultPermission = config.defaultPermission;
  const postTypes = config.post_types;
  config.permissions;
  const __ = doGetter(config.i18n);
  let formRef;
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
  function removePermission(index, event) {
    event.preventDefault();
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
  const click_handler = () => formRef.submit();
  const click_handler_1 = () => $$invalidate(0, config.permissions = [defaultPermission, ...config.permissions], config);
  const keypress_handler = (index, e) => onKeypress(e, index);
  const click_handler_2 = (permission, capIndex, each_value, index) => {
    permission.capabilities.splice(capIndex, 1);
    $$invalidate(0, each_value[index].capabilities = permission.capabilities, config);
  };
  const click_handler_3 = (index) => moveUp(index);
  const click_handler_4 = (index) => moveDown(index);
  const click_handler_5 = (index, e) => removePermission(index, e);
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
    defaultPermission,
    postTypes,
    __,
    onKeypress,
    removePermission,
    moveUp,
    moveDown,
    click_handler,
    click_handler_1,
    keypress_handler,
    click_handler_2,
    click_handler_3,
    click_handler_4,
    click_handler_5,
    form_binding
  ];
}
class PermissionEditor extends SvelteComponent {
  constructor(options) {
    super();
    init(this, options, instance$1, create_fragment$1, safe_not_equal, { config: 0 }, null, [-1, -1]);
  }
}
function get_each_context(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[9] = list[i];
  child_ctx[10] = list;
  child_ctx[11] = i;
  return child_ctx;
}
function get_each_context_1(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[12] = list[i];
  return child_ctx;
}
function get_each_context_2(ctx, list, i) {
  const child_ctx = ctx.slice();
  child_ctx[9] = list[i];
  child_ctx[11] = i;
  return child_ctx;
}
function create_if_block(ctx) {
  let div2;
  let p;
  let t1;
  let div1;
  let div0;
  let t2;
  let each_value_2 = ctx[2].permissions;
  let each_blocks_1 = [];
  for (let i = 0; i < each_value_2.length; i += 1) {
    each_blocks_1[i] = create_each_block_2(get_each_context_2(ctx, each_value_2, i));
  }
  let each_value = ctx[2].permissions;
  let each_blocks = [];
  for (let i = 0; i < each_value.length; i += 1) {
    each_blocks[i] = create_each_block(get_each_context(ctx, each_value, i));
  }
  return {
    c() {
      div2 = element("div");
      p = element("p");
      p.textContent = `${ctx[3]("Permissions")}`;
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
      attr(div2, "class", "flex-1");
    },
    m(target, anchor) {
      insert(target, div2, anchor);
      append(div2, p);
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
      if (dirty & 6) {
        each_value_2 = ctx2[2].permissions;
        let i;
        for (i = 0; i < each_value_2.length; i += 1) {
          const child_ctx = get_each_context_2(ctx2, each_value_2, i);
          if (each_blocks_1[i]) {
            each_blocks_1[i].p(child_ctx, dirty);
          } else {
            each_blocks_1[i] = create_each_block_2(child_ctx);
            each_blocks_1[i].c();
            each_blocks_1[i].m(div0, null);
          }
        }
        for (; i < each_blocks_1.length; i += 1) {
          each_blocks_1[i].d(1);
        }
        each_blocks_1.length = each_value_2.length;
      }
      if (dirty & 15) {
        each_value = ctx2[2].permissions;
        let i;
        for (i = 0; i < each_value.length; i += 1) {
          const child_ctx = get_each_context(ctx2, each_value, i);
          if (each_blocks[i]) {
            each_blocks[i].p(child_ctx, dirty);
          } else {
            each_blocks[i] = create_each_block(child_ctx);
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
    d(detaching) {
      if (detaching)
        detach(div2);
      destroy_each(each_blocks_1, detaching);
      destroy_each(each_blocks, detaching);
    }
  };
}
function create_each_block_2(ctx) {
  let div;
  let t0_value = ctx[9].name + "";
  let t0;
  let t1;
  let div_class_value;
  let mounted;
  let dispose;
  function click_handler() {
    return ctx[5](ctx[11]);
  }
  return {
    c() {
      div = element("div");
      t0 = text(t0_value);
      t1 = space();
      attr(div, "class", div_class_value = "shadow font-bold hover:bg-blue-500 hover:text-white cursor-pointer rounded flex-1 p-2 " + (ctx[1].name === ctx[9].name && "bg-blue-500 text-white"));
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
      if (dirty & 2 && div_class_value !== (div_class_value = "shadow font-bold hover:bg-blue-500 hover:text-white cursor-pointer rounded flex-1 p-2 " + (ctx[1].name === ctx[9].name && "bg-blue-500 text-white"))) {
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
function create_else_block(ctx) {
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
function create_if_block_1(ctx) {
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
function create_each_block_1(ctx) {
  let label;
  let input;
  let t0;
  let span;
  let t1_value = ctx[12] + "";
  let t1;
  let t2;
  let mounted;
  let dispose;
  ctx[7][0][ctx[11]] = [];
  function input_change_handler() {
    ctx[6].call(input, ctx[9], ctx[11]);
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
      input.__value = ctx[12];
      input.value = input.__value;
      attr(input, "name", "" + (ctx[2].names.permission + "[" + ctx[9].name + "][capabilities][]"));
      ctx[7][0][ctx[11]].push(input);
    },
    m(target, anchor) {
      insert(target, label, anchor);
      append(label, input);
      input.checked = ~ctx[0][ctx[9].name].capabilities.indexOf(input.__value);
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
      if (dirty & 5) {
        input.checked = ~ctx[0][ctx[9].name].capabilities.indexOf(input.__value);
      }
    },
    d(detaching) {
      if (detaching)
        detach(label);
      ctx[7][0][ctx[11]].splice(ctx[7][0][ctx[11]].indexOf(input), 1);
      mounted = false;
      dispose();
    }
  };
}
function create_each_block(ctx) {
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
    if (ctx2[9].logged_required)
      return create_if_block_1;
    return create_else_block;
  }
  let current_block_type = select_block_type(ctx);
  let if_block = current_block_type(ctx);
  let each_value_1 = ctx[9].capabilities || [];
  let each_blocks = [];
  for (let i = 0; i < each_value_1.length; i += 1) {
    each_blocks[i] = create_each_block_1(get_each_context_1(ctx, each_value_1, i));
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
      toggle_class(div2, "hidden", ctx[9].name !== ctx[1].name);
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
      if (dirty & 5) {
        each_value_1 = ctx2[9].capabilities || [];
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
      if (dirty & 6) {
        toggle_class(div2, "hidden", ctx2[9].name !== ctx2[1].name);
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
function create_fragment(ctx) {
  let div5;
  let div4;
  let div1;
  let label0;
  let div0;
  let t1;
  let textarea;
  let t2;
  let div3;
  let label1;
  let div2;
  let t4;
  let input;
  let t5;
  let if_block = ctx[1] && create_if_block(ctx);
  return {
    c() {
      div5 = element("div");
      div4 = element("div");
      div1 = element("div");
      label0 = element("label");
      div0 = element("div");
      div0.textContent = `${ctx[3]("Abstract")}`;
      t1 = space();
      textarea = element("textarea");
      t2 = space();
      div3 = element("div");
      label1 = element("label");
      div2 = element("div");
      div2.textContent = `${ctx[3]("Decorative image")}`;
      t4 = space();
      input = element("input");
      t5 = space();
      if (if_block)
        if_block.c();
      attr(textarea, "name", ctx[2].names.abstract);
      attr(textarea, "class", "w-full");
      textarea.value = ctx[2].abstract;
      attr(div1, "class", "flex flex-col");
      attr(input, "type", "file");
      attr(input, "accept", "image/png, image/jpeg");
      attr(input, "name", ctx[2].names.image);
      attr(div3, "class", "flex flex-col");
      attr(div4, "class", "space-y-3 flex-1");
      attr(div5, "class", "flex flex-row space-x-2");
    },
    m(target, anchor) {
      insert(target, div5, anchor);
      append(div5, div4);
      append(div4, div1);
      append(div1, label0);
      append(label0, div0);
      append(label0, t1);
      append(label0, textarea);
      append(div4, t2);
      append(div4, div3);
      append(div3, label1);
      append(label1, div2);
      append(label1, t4);
      append(label1, input);
      append(div5, t5);
      if (if_block)
        if_block.m(div5, null);
    },
    p(ctx2, [dirty]) {
      if (ctx2[1]) {
        if (if_block) {
          if_block.p(ctx2, dirty);
        } else {
          if_block = create_if_block(ctx2);
          if_block.c();
          if_block.m(div5, null);
        }
      } else if (if_block) {
        if_block.d(1);
        if_block = null;
      }
    },
    i: noop,
    o: noop,
    d(detaching) {
      if (detaching)
        detach(div5);
      if (if_block)
        if_block.d();
    }
  };
}
function instance($$self, $$props, $$invalidate) {
  const defaultConfig = {
    names: {
      abstract: "mc_metabox_abstract",
      image: "mc_metabox_image",
      permission: "mc_metabox_permission"
    },
    abstract: "",
    permissions: [],
    selections: {},
    i18n: {}
  };
  let { config = {} } = $$props;
  if (!config.permissions)
    config.permissions = [];
  const finalConfig = __spreadValues(__spreadValues({}, defaultConfig), config || {});
  doDefault(finalConfig.permissions, finalConfig.selections || [], (x) => x.name, (src) => ({
    name: src.name,
    post_types: [],
    capabilities: []
  }));
  const selections = arrayAsMap(finalConfig.selections, (x) => x.name);
  const __ = doGetter(finalConfig.i18n);
  let currentTab = finalConfig.permissions[0];
  const $$binding_groups = [[]];
  const click_handler = (index) => $$invalidate(1, currentTab = finalConfig.permissions[index]);
  function input_change_handler(item, index) {
    selections[item.name].capabilities = get_binding_group_value($$binding_groups[0][index], this.__value, this.checked);
    $$invalidate(0, selections);
  }
  $$self.$$set = ($$props2) => {
    if ("config" in $$props2)
      $$invalidate(4, config = $$props2.config);
  };
  return [
    selections,
    currentTab,
    finalConfig,
    __,
    config,
    click_handler,
    input_change_handler,
    $$binding_groups
  ];
}
class MetaPost extends SvelteComponent {
  constructor(options) {
    super();
    init(this, options, instance, create_fragment, safe_not_equal, { config: 4 });
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
export { renderMetaPost, renderPermissionEditor, renderUserPermissions };
