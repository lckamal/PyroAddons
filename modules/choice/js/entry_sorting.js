(function($)
{
	$(function() {

		$('table tbody').sortable({
			handle: 'td',
			helper: 'clone',
			start: function(event, ui) {
				$('tr').removeClass('alt');
			},
			update: function() {
				order = new Array();
				$('tr', this).each(function(){
					order.push( $(this).find('input[name="action_to[]"]').val() );
				});
				order = order.join(',');
				var csrf_hash_name = $('input[name=csrf_hash_name]').val();
				$.post(SITE_URL+'admin/choice/ajax/entry_order_update', { order: order, csrf_hash_name: csrf_hash_name}, function() {
				});
			},
			stop: function(event, ui) {
				$("tbody tr:nth-child(even)").livequery(function () {
					$(this).addClass("alt");
				});
			}
			
		}).disableSelection();
		
	});
})(jQuery);