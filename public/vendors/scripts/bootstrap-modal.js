$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'X-Requested-With':'XMLHttpRequest'
    },
    baseUrl: BASE_URL
});
var originalAjax = $.ajax;
$.ajax = function(options) {
    if (typeof options.url === 'string') {
        options.url = $.ajaxSetup().baseUrl + options.url;
    }
    return originalAjax(options);
};


window.modal= function(header,body,footer,size=""){
    if(size!=""){
        $("#main-modal-size").addClass("modal-dialog "+size);
    }
    $('#main-modal-header').html(header);
    $('#main-modal-body').html(body);
    $('#main-modal-footer').html(footer);
    $('#main-modal').modal({backdrop:'static'})
}
window.hideModal=function(){
    $('#main-modal').modal('hide');
}
window.fullModal=function(html){
    $('#main-modal').html(html);
    $('#main-modal').modal({backdrop:'static'})
}
window.recoveryModal = function(){
    var html=`<div class="modal-dialog" id="main-modal-size">
        <div class="modal-content">
            <div class="modal-header" id="main-modal-header">
                
            </div>
            <div class="modal-body" id="main-modal-body">
                
            </div>
            <div class="modal-footer" id="main-modal-footer">
                
            </div>
        </div>
    </div>`;
    $('#main-modal').html(html);
}
window.modalHeader=function(title){
    return `<h4 class="modal-title">`+title+`</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>`;
}
window.modalFooter= function(clickableName,buttonName="Submit"){
    return `<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-dark" onclick="`+clickableName+`(this)">${buttonName}</button>`;
}
window.modalFooterSave=function(clickableName){
    return `<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-dark" onclick="`+clickableName+`(this)">Save</button>`;
}
window.modalFooterNoClose=function(clickableName){
    return `<button type="button" class="btn btn-dark" onclick="`+clickableName+`(this)">Save</button>`;
}
window.modalFooterNoCloseSubmit=function(clickableName){
    return `<button type="button" class="btn btn-dark" onclick="`+clickableName+`(this)">Submit</button>`;
}
window.showLoader =function(){
    $('#form-loader').show();
    $(".pace-done").addClass("form-loader-overflow");
}
window.hideLoader= function(){
    $('#form-loader').hide();
    $(".pace-done").removeClass("form-loader-overflow");
}

$(document).ajaxError(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
    if(xhr.status===401){
        recoveryModal();
        var html=`<div class="modal-dialog modal-sm" style="max-width: 370px !important;" id="main-modal-size">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-exclamation-triangle text-red mr-1"></i> Session Expired</h5>
                </div>
                <div class="modal-body">
                    <p>Your session has expired.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="location.reload()" class="btn btn-danger">OK</button>
                </div>
            </div>
        </div>`;
        fullModal(html);
        //location.reload();
    }
});
window.isValidSlug = function(slug) {
    var slugRegex = /^[a-z0-9]+(?:-[a-z0-9]+)*$/;
    return slugRegex.test(slug);
}

window.fixConterbyBr = function(content, lengthChange=10){
    var words = content.trim().split(/\s+/);
    var lines = [];
    for (var i = 0; i < words.length; i += lengthChange) {
        lines.push(words.slice(i, i + lengthChange).join(" "));
    }
    return lines.join("<br>");
}
window.hideContentByWords= function(content,lengthCount=50) {
    let words = content.split(" ");
    let first50Words = words.slice(0, lengthCount);
    let truncatedContent = first50Words.join(" ");
    truncatedContent += "...";
    return truncatedContent;
}