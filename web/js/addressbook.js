$(document).ready(function () {
    const $modal = $('#exampleModalCenter');
    $('.deleteBtn').on('click', function () {
        $modal.addClass('show');
        $modal.show();
        let removeUrl = $(this).attr('data-remove-url');
        $('.modalActionButton').attr('data-remove-url', removeUrl);
    });

    $(".modalActionButton").click(function (e) {
        e.preventDefault();
        const removeUrl = $(this).attr('data-remove-url');
        const selectedItem = $(`.deleteBtn[data-remove-url="${removeUrl}"]`);
        const selectedRow = selectedItem.closest('tr');
        $.ajax({
            url: removeUrl,
            type: 'POST',
            data: {},
            contentType: 'text',
            success: function () {
                closeModal();
                selectedRow.remove();
                console.log($('.addressBookList >tbody >tr').length);
                if ($('.addressBookList >tbody >tr').length === 1) {
                    $('table').remove();
                }
            }
        });
    });

    $(".modalClose").click(function (e) {
        closeModal();
    });

    function closeModal() {
        $modal.removeClass('show');
        $modal.hide();
    }
});
