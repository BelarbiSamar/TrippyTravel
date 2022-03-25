// jQuery(document).ready(function () {
//     jQuery('.add-another-collection').click(function (e) {
//         var list = $("#diplomes-fields-list");
//         var counter = list.data('widget-counter') | list.children().length;
//         var newWidget = list.attr('data-prototype');
//         newWidget = newWidget.replace(/__name__/g, counter);
//         counter++;
//         list.data('widget-counter', counter);
//
//         var newElem = jQuery(list.attr('data-widget-tags')).html(newWidget);
//         newElem.appendTo(list);
//         newElem.append('<a href="#" class="remove-tag" style="color: darkred">remove</a>');
//         $('.remove-tag').click(function(e) {
//             e.preventDefault();
//
//             $(this).parent().remove();
//
//         });
//     });
// });
// On ajoute un nouveau champ à chaque clic sur le lien d'ajout.
$('.btn-collection-add').click(function (e) {
    addDocumentation($($(this).data('prototype-add')));
    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
    return false;
});

// La fonction qui ajoute un formulaire CategoryType
function addDocumentation($container) {
    var index = $container.find(':input').length;
    // Dans le contenu de l'attribut « data-prototype », on remplace :
    // - le texte "__name__" qu'il contient par le numéro du champ
    var template = $container.attr('data-prototype').replace(/__name__/g, index);
    // On crée un objet jquery qui contient ce template
    var $prototype = $(template);
    // On ajoute le prototype modifié à la fin de la balise <div>
    $container.append($prototype);
    // Enfin, on incrémente le compteur pour que le prochain ajout se fasse avec un autre numéro
    index++;
}

// Ajout du listener sur le clic du lien pour effectivement supprimer l'entrée de la collection.
$(document).on('click', '.btn-collection-delete', function (e) {
    $(this).closest('.panel').remove();
    e.preventDefault(); // évite qu'un # apparaisse dans l'URL
    return false;
});
$(document).ready(function () {
    $('#btn-ok').click(function (e) {
        let $form = $(this).closest('form');
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Vous ne pourrez pas revenir en arrière !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimez-le !',
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                $form.submit();
            }
        })
    });
});
// $(document).on('keyup', '.search', function() {
//     var searchVal = $(this).val();
//     $.ajax({
//         url: Routing.generate('ajax_search_excursion'),
//         type: "POST",
//         dataType: "json",
//         data: {
//             "search": searchVal
//         },
//         success: function (response)
//         {
//             console.log(response);
//         }
//     });
// });
jQuery(document).ready(function () {
    var searchRequest = null;
    $("#search").keyup(function () {
        var minlength = 0;
        var that = this;
        var value = $(this).val();
        var entitySelector = $("#entitiesNav").html('');
        if (value.length >= minlength) {
            if (searchRequest != null)
                searchRequest.abort();
            searchRequest = $.ajax({
                type: "POST",
                url: "/admin-dashboard/excursionsearch",
                data: {
                    'q': value
                },
                dataType: "text",
                success: function (msg) {
                    // we need to check if the value is the same
                    if (value === $(that).val()) {
                        var result = JSON.parse(msg);
                        $.each(result, function (key, arr) {
                            $.each(arr, function (id, value) {
                                if (key === 'excursions') {
                                    if (id !== 'error') {
                                        console.log(value[1]);
                                        var tr = '<tr><td>' + value[0] + '</td><td>' + value[1] + '</td><td>' + value[2] + '</td><td>' + value[3] + '</td>'
                                        tr += '<td>' +
                                            '                                                <div class="btn-group">' +
                                            '                                                    <a href="/admin-dashboard/excursion/' + value[0] + '" class="btn btn-secondary btn-icon-text">' +
                                            '                                                        <i class="bi bi-eye"></i>' +
                                            '                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">' +
                                            '                                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"></path>' +
                                            '                                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"></path>' +
                                            '                                                        </svg>' +
                                            '                                                    </a>' +
                                            '                                                    <a href="/admin-dashboard/excursion/' + value[0] + '/edit" class="btn btn-secondary">' +
                                            '                                                        <i class="bi bi-pen"></i>' +
                                            '                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">' +
                                            '                                                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"></path>' +
                                            '                                                        </svg>' +
                                            '                                                    </a>' +
                                            '                                                    <form class="swa-confirm" method="post" action="/admin-dashboard/excursion/' + value[0] + '" xmlns="http://www.w3.org/1999/html">' +
                                            '    <input type="hidden" name="_token" value="' + value[4] + '">' +
                                            '' +
                                            '    <a type="button" id="btn-ok" class=" btn btn-outline-danger btn-icon-text"><i class="mdi mdi-delete"></i></a>' +
                                            '</form>' +
                                            '' +
                                            '                                                </div>' +
                                            '                                            </td>'
                                        tr += "</tr>"
                                        entitySelector.append(tr);
                                    } else {
                                        entitySelector.append('<tr><td>' + value + '</td></tr>');
                                    }
                                }
                            });
                        });
                    }
                }
            });
        }
    });
});
$(document).on('click', 'th', function() {
    var table = $(this).parents('table').eq(0);
    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
    this.asc = !this.asc;
    if (!this.asc) {
        rows = rows.reverse();
    }
    table.children('tbody').empty().html(rows);
});

function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index),
            valB = getCellValue(b, index);
        return $.isNumeric(valA) && $.isNumeric(valB) ?
            valA - valB : valA.localeCompare(valB);
    };
}

function getCellValue(row, index) {
    return $(row).children('td').eq(index).text();
}
