<script src="/lib/rium/index.js"></script>
<script src="/lib/vue/index.js"></script>
<script src="/lib/element-ui/index.js"></script>
<script src="/lib/marked/index.js"></script>
<script src="/lib/clipboard/index.js"></script>
<script>
    new ClipboardJS('.clipboard-btn');
    $bindVue(Vue)
    Vue.prototype.$open = uri => { window.open(uri, '_blank') }
    Vue.prototype.$marked = md => marked(md)
</script>