export default {
    bind: function(el, binding, vNode) {
        // Provided expression must evaluate to a function.
        if (typeof binding.value !== 'function') {
            let compName = vNode.context.name;
            let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function.`;
            
            if (compName) { 
                warn += ` Found in component '${compName}'.`; 
            }
            
            console.warn(warn);
        }

        let handler = (e) => {
            if ((!el.contains(e.target) && el !== e.target)) {
                binding.value(e)
            }
        }

        el.__vueClickOutside__ = handler;

        document.addEventListener('click', handler);
    },
      
    unbind: function(el, binding) {
        document.removeEventListener('click', el.__vueClickOutside__);

        el.__vueClickOutside__ = null;
    }
}
