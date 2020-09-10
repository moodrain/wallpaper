if(this.msg) {
    this.$notify({
        message: this.msg,
        type: 'success',
        duration: 5000,
    })
}
if(this.errMsg) {
    this.$notify({
        message: this.errMsg,
        type: 'warning',
        duration: 0,
    })
}
let images = document.querySelectorAll('.preview img')
images.forEach(e => {
    e.addEventListener('click', e => {
        this.previewImage(e.target.src, e.target.naturalWidth)
        e.stopPropagation()
    })
})
document.querySelector('#loading').style['z-index'] = -1
