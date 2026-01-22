// Componentes JS - Agua Colegial

function initDynamicCalculation(inputSelector, displaySelector, animationClass = 'input-changed') {
    $(inputSelector).on('input', function() {
        let total = 0;
        $(inputSelector).each(function() {
            const value = parseInt($(this).val()) || 0;
            total += value;
        });
        $(displaySelector).css('transform', 'scale(1.2)');
        $(this).addClass(animationClass);
        setTimeout(() => {
            $(displaySelector).text(total.toLocaleString());
            $(displaySelector).css('transform', 'scale(1)');
            $(this).removeClass(animationClass);
        }, 150);
    });
}

function initAutoSelect(selector) {
    $(selector).on('click', function() {
        $(this).select();
    });
}

function initRealtimeValidation(selector, minValue = 0) {
    $(selector).on('input', function() {
        const val = parseInt($(this).val());
        if (val < minValue) {
            $(this).val(minValue);
        }
    });
}

function initInputHoverEffect(selector) {
    $(selector).on('focus', function() {
        $(this).parent().css('transform', 'scale(1.02)');
    }).on('blur', function() {
        $(this).parent().css('transform', 'scale(1)');
    });
}

function initKeyboardShortcuts(formSelector, saveRoute) {
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            $(formSelector).submit();
        }
        if (e.key === 'Escape' && saveRoute) {
            window.location.href = saveRoute;
        }
    });
}

function initFormSubmitAnimation(formSelector) {
    $(formSelector).on('submit', function(e) {
        const btn = $(this).find('button[type="submit"]');
        btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...')
           .prop('disabled', true);
        $('.modern-card, .section-box').css({
            'opacity': '0.7',
            'pointer-events': 'none'
        });
    });
}

function initSectionAnimations(selector = '.section-box, .modern-card') {
    $(selector).each(function(index) {
        $(this).css({ 'opacity': '0', 'transform': 'translateY(20px)' });
        setTimeout(() => {
            $(this).css({
                'opacity': '1',
                'transform': 'translateY(0)',
                'transition': 'all 0.5s ease-out'
            });
        }, index * 100);
    });
}

function initTooltips(selector, message = 'Ingrese la cantidad') {
    $(selector).attr('title', message);
}

function animateCounter(element, start, end, duration = 1000) {
    const range = end - start;
    const increment = end > start ? 1 : -1;
    const stepTime = Math.abs(Math.floor(duration / range));
    let current = start;
    const timer = setInterval(function() {
        current += increment;
        $(element).text(current.toLocaleString());
        if (current === end) {
            clearInterval(timer);
        }
    }, stepTime);
}

function initModernDataTable(selector, options = {}) {
    const defaultOptions = {
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Todos"]],
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ entradas",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
            "sInfoFiltered": "(filtrado de un total de _MAX_ entradas)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        drawCallback: function() {
            $('.dataTables_wrapper tr').addClass('animate__animated animate__fadeIn animate__faster');
        }
    };
    return $(selector).DataTable($.extend(true, defaultOptions, options));
}

function confirmDelete(message = '¿Está seguro de eliminar este registro?', title = '¿Está seguro?') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        buttonsStyling: true,
        customClass: {
            confirmButton: 'btn-modern btn-danger',
            cancelButton: 'btn-modern btn-secondary'
        }
    });
}

function showLoader(message = 'Procesando...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showConfirmButton: false,
        didOpen: () => { Swal.showLoading(); }
    });
}

function hideLoader() {
    Swal.close();
}

function formatNumber(number, decimals = 0) {
    return new Intl.NumberFormat('es-ES', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}

function formatCurrency(amount, currency = 'COP') {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: currency
    }).format(amount);
}

function initModernSelect2(selector, placeholder = 'Seleccione una opción') {
    $(selector).select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: placeholder,
        allowClear: true,
        language: {
            noResults: function() { return "No se encontraron resultados"; },
            searching: function() { return "Buscando..."; }
        }
    });
}

function initModernSelect2NoSearch(selector, placeholder = 'Seleccione una opción') {
    $(selector).select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: placeholder,
        allowClear: true,
        minimumResultsForSearch: Infinity
    });
}

function showNotification(type, message, title = '') {
    const titles = {
        success: '¡Éxito!',
        error: 'Error',
        warning: 'Advertencia',
        info: 'Información'
    };
    toastr[type](message, title || titles[type] || 'Notificación');
}

function initUnsavedChangesWarning(formSelector) {
    let formChanged = false;
    $(formSelector + ' :input').on('change', function() { formChanged = true; });
    $(window).on('beforeunload', function() {
        if (formChanged) return '¿Está seguro de salir? Los cambios no guardados se perderán.';
    });
    $(formSelector).on('submit', function() { formChanged = false; });
}

function initImagePreview(inputSelector, previewSelector) {
    $(inputSelector).on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $(previewSelector).attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
}

function copyToClipboard(text, successMessage = 'Copiado al portapapeles') {
    navigator.clipboard.writeText(text).then(function() {
        showNotification('success', successMessage);
    }, function(err) {
        showNotification('error', 'Error al copiar: ' + err);
    });
}

window.ModernComponents = {
    initDynamicCalculation,
    initAutoSelect,
    initRealtimeValidation,
    initInputHoverEffect,
    initKeyboardShortcuts,
    initFormSubmitAnimation,
    initSectionAnimations,
    initTooltips,
    animateCounter,
    initModernDataTable,
    confirmDelete,
    showLoader,
    hideLoader,
    formatNumber,
    formatCurrency,
    initModernSelect2,
    initModernSelect2NoSearch,
    showNotification,
    initUnsavedChangesWarning,
    initImagePreview,
    copyToClipboard
};
