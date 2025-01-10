<script>
    $(document).ready(function () {
        
        'use strict';

        $('body').tooltip({
            selector: '.component-manage , .manage-drag'
        });
	    
        const list1 = $('#componentList');
        const list2 = $('#pageComponent');
        
        const onAddToList1 = function (evt) {
            const itemEl = evt.item;
            $(itemEl).find('.manage-drag').attr('title', 'Add to Page');
            $(itemEl).find('.manage-drag').html(`<span class="drag-svg"> <x-svg i="com-plus"/> </span>`);
        };

        const onAddToList2 = function (evt) {
            const itemEl = evt.item;
            $(itemEl).find('.manage-drag').attr('title', 'Move to Component List');
            $(itemEl).find('.manage-drag').html(`<span class="drag-svg"><x-svg i="delete"/> </span>`);
        };

        const onMoveToList1 = function (event) {
            const listItem = $(event.target).closest('.item');
            const clonedItem = listItem.clone();
            clonedItem.find('.manage-drag').attr('title', 'Add to Page');
            clonedItem.find('.drag-svg').html(`<span class="drag-svg"> <x-svg i="com-plus"/> </span>`);
            listItem.remove();
            list1.append(clonedItem);
            $(this).tooltip('hide');
        };

        const onMoveToList2 = function (event) {
			const listItem = $(event.target).closest('.item');
			const clonedItem = listItem.clone();
            clonedItem.find('.manage-drag').attr('title', 'Move to Component List');
			clonedItem.find('.drag-svg').html(`<span class="drag-svg"><x-svg i="delete"/> </span>`);
			listItem.remove();
			list2.append(clonedItem);
            $(this).tooltip('hide');
            notifyEvs('success', 'Component moved to page');
        };

        new Sortable(list1[0], {
            group: 'shared',
			handle: '.details',
            animation: 150,
            dataIdAttr: 'data-index',
            onAdd: onAddToList1,
            onUnchoose: function() {
                $('.drop-text').css('display', 'none');
                list2.removeClass('drop-here');
                
            }
        });

        new Sortable(list2[0], {
            group: 'shared',
            handle: '.details',
            animation: 150,
            onAdd: onAddToList2,
            onEnd:function (evt) {
            
            },
        });
        list2.on('click', '.manage-drag', onMoveToList1);
        list1.on('click', '.manage-drag', onMoveToList2);

        $('#page_title').on('input', function() {
            const title = $(this).val();
            const slug = generateSlug(title);
            $('.page_slug').val(slug);
        });

        $('#filterComponentCategory').on('change', function() {

            $('.loading').removeAttr('hidden').prop('disabled', true);

            var pageId = "{{ $page->id ?? 0 }}";
            var category = $(this).val();
            var componentIds = $('#pageComponent .item').map(function() {
                return $(this).data('index');
            }).get();
            var componentIdsJSON = JSON.stringify(componentIds);

            $.get("{{ route('admin.page.component-filter') }}", {
                category: category,
                page_id: pageId,
                component_ids: componentIdsJSON
            }, function(data) {
                $('#componentList').html(data);
                $('.loading').attr('hidden', true).prop('disabled', true);
            });
        });




    });

</script>