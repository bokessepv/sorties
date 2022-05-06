let $ville = $('#sortie_ville')

$ville.change(function (){
    let $form = $(this).closest('form')

    let data = {}

    data[$ville.attr('name')] = $ville.val()

    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: data,
        complete: function(html) {
            $('#sortie_lieu').replaceWith(
                $(html.responseText).find('#sortie_lieu')
            );
        }
    })
})
function log($msg)
{
    console.log($msg)
}