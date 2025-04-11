
function openEditModal(id, nombreActual) {
    $('#folioId').val(id);
    $('#folioName').val(nombreActual);
    $('#editModal').modal('show');
}

$('#modalForm').submit(function (e) {
    e.preventDefault();
    const id = $('#folioId').val();
    const name = $('#folioName').val();

    $.post('update_name.php', { id, name }, function (nuevoFolio) {
        $('#folio-' + id + ' td:first')
            .text(nuevoFolio)
            .attr('title', nuevoFolio);
        $('#editModal').modal('hide');
    });
});

function toggleStatus(folioId) {
    const statusIcon = $('[data-folio-id="'+folioId+'"]');
    const currentStatus = statusIcon.hasClass('ri-checkbox-line') ? 'hecho' : 'no-hecho';
    const newStatus = currentStatus === 'hecho' ? 'no-hecho' : 'hecho';
    
    $.post('update_status.php', { id: folioId, status: newStatus }, function () {
        // Cambiar el ícono y el color dependiendo del nuevo estado
        statusIcon.removeClass('ri-checkbox-line ri-checkbox-blank-line')
                  .addClass(newStatus === 'hecho' ? 'ri-checkbox-line' : 'ri-checkbox-blank-line')
                  .removeClass('hecho no-hecho')
                  .addClass(newStatus === 'hecho' ? 'hecho' : 'no-hecho');
        
        // Actualizar los contadores
        let hecho = parseInt($('#status-hecho-count').text());
        let noHecho = parseInt($('#status-no-hecho-count').text());

        if (newStatus === 'hecho') {
            $('#status-hecho-count').text(hecho + 1);
            $('#status-no-hecho-count').text(noHecho - 1);
        } else {
            $('#status-hecho-count').text(hecho - 1);
            $('#status-no-hecho-count').text(noHecho + 1);
        }
    });
}

function toggleAllStatus() {
    const statusIcon = $('#status-toggle');
    const currentStatus = statusIcon.hasClass('hecho') ? 'hecho' : 'no-hecho';
    const newStatus = currentStatus === 'hecho' ? 'no-hecho' : 'hecho';
    
    // Cambiar todos los íconos en la tabla
    $('td .ri-checkbox-blank-line, td .ri-checkbox-line').each(function() {
        const statusIcon = $(this);
        statusIcon.removeClass('ri-checkbox-line ri-checkbox-blank-line')
                  .addClass(newStatus === 'hecho' ? 'ri-checkbox-line' : 'ri-checkbox-blank-line')
                  .removeClass('hecho no-hecho')
                  .addClass(newStatus === 'hecho' ? 'hecho' : 'no-hecho');
    });

    // Actualizar el contador de todos
    let hecho = newStatus === 'hecho' ? $('td .ri-checkbox-blank-line').length : 0;
    let noHecho = newStatus === 'no-hecho' ? $('td .ri-checkbox-line').length : 0;
    
    $('#status-hecho-count').text(hecho);
    $('#status-no-hecho-count').text(noHecho);
}