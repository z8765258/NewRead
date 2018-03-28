/**
 * Created by PVer on 2018/2/28.
 */
$(function() {
    $("#file_upload").uploadify({
        swf           : swf,
        uploader      : image_upload_url,
        buttonText    :'文件上传',
        // fileTypeDesc  :'Image files',
        fileObjName   :'file',
        // fileTypeExts  :'*.gif;*.jpg;*.png',
        onUploadSuccess : function (file,data,response) {
            if(response){

                var obj = JSON.parse(data);
                $('#upload_org_code_img').attr("src",obj.data);
                $('#file_upload_image').attr("src",obj.data);
                $('#upload_org_code_img').show();
            }
        }
    });
});