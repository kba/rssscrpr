var resultList = $("div#result-list");
var resultCounter = $("#results-found")

function enableForm()
{
  $('input[type=checkbox][data-enable]').each(function(){
    var checked = $(this).prop('checked');
    var toggleName = $(this).attr('data-enable');
    $('*[name=' + toggleName + ']').prop('disabled', ! checked);
  });
}
enableForm();

$("input").on('click', enableForm);

$("#input-group-url button").on('click', function(e) {
  var serializedForm = $('form#cyos-form').serialize();
  resultList.empty();
  resultCounter.html("0");

  var uri = $("#input-group-url input").val();
  var apiUri = 'api.php?url=' + encodeURIComponent(uri) + '&' + serializedForm;

  $("a#api-uri").attr('href', apiUri);

  $.get(apiUri, function(data) {
    resultCounter.html($('item', data).size());
    var i = 1;
    $('item', data).each(function() {
      var itemDiv = $('<div class="panel panel-default"/>');
      var itemLink = $("link", this).text();
      itemDiv.append($("<div class='panel-heading'>")
                     .append(i++ + ". ").append($('title', this)));
      itemDiv.append($("<div class='panel-body'>")
                     .append($("<a>").append(itemLink).attr('href', itemLink))
                     .append($("<span>").append($('author', this)))
                     .append($("<p>").append($('description', this))));
      resultList.append(itemDiv);
    });
  });
});

