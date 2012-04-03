function opsi_berantai(id_selectbox_asal,id_selectbox_tujuan,url_sumber_data,get_param){
	get_param = (get_param)?get_param:'id'; // jika get_param ga diset maka otomatis terisi id
	$('#'+id_selectbox_asal).on('change',function(el){
		$(this).find('option').each(function(i,el){
			if($(this).attr('selected') == "selected"){
				$.ajax({
					type: 'GET',
					url: url_sumber_data+get_param+':'+$(this).attr('value'),
					success: function(data) {
						$('#'+id_selectbox_tujuan).html(data);
					},
					error: function(XMLHttpRequest,textStatus,errorThrown){
						$('#'+id_selectbox_tujuan).html('Data '+errorThrown);
					}
				});
			}
		});
	});
}