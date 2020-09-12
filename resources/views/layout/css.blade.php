<link href="/lib/element-ui/index.css" rel="stylesheet">
<link href="/lib/mdui/index.css" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        scrollbar-width: none
    }
    html, body, #app {
        width: 100%;
        height: 100%;
    }
    a {
        text-decoration: none;
        color: black;
    }
    ::-webkit-scrollbar {
        width: 0;
        height: 0
    }
    .el-tag {
        margin: 2px;
    }
    .mdui-typo table {
        border-collapse: collapse;
        border-spacing: 0;
        empty-cells: show;
        border: 1px solid #cbcbcb;
    }
    .mdui-typo table tbody tr td,th {
        margin: 20px;
        padding: 20px;
        border-width: 0 0 1px 0;
        border-bottom: 1px solid #cbcbcb
    }
    .mdui-typo table thead {
        background-color: #e0e0e0;
        color: #000;
        text-align: left;
        vertical-align: bottom
    }
    .image-card {
        display: inline-block;
        width: min-content;
        height: min-content;
        margin: 5px;
    }
    .image-card .el-card__body {
        padding: 2px !important;
    }
    .image {
        width: 200px;
        height: 200px;
    }
    .edit-dialog {
        width: 400px;
    }
    .card-select {
        border: 1px solid dodgerblue;
    }
</style>