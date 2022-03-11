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