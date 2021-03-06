$(function () {
	var inputFile = $('input[name=file]');
	var uploadURI = $('#form-upload').attr('action');
	var progressBar = $('#progress-bar');
console.log(uploadURI);
	//listFilesOnServer();

	$('#save').on('click', function(event) {
		var fileToUpload = inputFile[0].files[0];
		// make sure there is file to upload
//console.log(fileToUpload);
event.preventDefault();
		if (fileToUpload != 'undefined') {
			// provide the form data
			// that would be sent to sever through ajax
			var formData = new FormData();
			formData.append("file", fileToUpload);
//formData.append("post", '$("#form_upload").serialize()');
			// now upload the file using $.ajax
var other_data = $('form').serializeArray();
    $.each(other_data,function(key,input){
        formData.append(input.name,input.value);
    });
			$.ajax({
				url: uploadURI,
				type: 'post',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					$("#well-form").fadeOut();
					$("#form-upload")[0].reset();
					$('.progress').hide();
					
					console.log(response);
				},
				xhr: function() {
					var xhr = new XMLHttpRequest();
					xhr.upload.addEventListener("progress", function(event) {
						if (event.lengthComputable) {
							var percentComplete = Math.round( (event.loaded / event.total) * 100 );
							// console.log(percentComplete);
							
							$('.progress').show();
							progressBar.css({width: percentComplete + "%"});
							progressBar.text(percentComplete + '%');
						};
					}, false);
					return xhr;
				}
			});
		}
	});

	$('body').on('click', '.remove-file', function () {
		var me = $(this);

		$.ajax({
			url: uploadURI,
			type: 'post',
			data: {file_to_remove: me.attr('data-file')},
			success: function() {
				me.closest('li').remove();
			}
		});

	})

	// function listFilesOnServer () {
	// 	var items = [];

	// 	$.getJSON(uploadURI, function(data) {
	// 		$.each(data, function(index, element) {
	// 			items.push('<li class="list-group-item">' + element  + '<div class="pull-right"><a href="#" data-file="' + element + '" class="remove-file"><i class="glyphicon glyphicon-remove"></i></a></div></li>');
	// 		});
	// 		$('.list-group').html("").html(items.join(""));
	// 	});
	// }

	$('body').on('change.bs.fileinput', function(e) {
		$('.progress').hide();
		progressBar.text("0%");
		progressBar.css({width: "0%"});
	});
});