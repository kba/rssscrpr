var resultList = $("div#result-list");
var resultCounter = $("#results-found")
var inputUrl = $("#input-group-url input");
var runButton = $("#input-group-url button");

function syncFormEnable(forceEnable) {
  $('span[data-on-select]').each(function() {
    var checkbox = $("input[type='checkbox']", this);
    var toggleName = checkbox.attr('data-enable');
    var checkBoxFor = $(this).attr('data-on-select');
    var selectedOption = $("select", $(this).parent()).find(':selected').text();

    if (checkBoxFor !== selectedOption) {
      $('*[name=' + toggleName + ']').removeClass('in');
      $('*[name=' + toggleName + '] input').prop('disabled', true);
      $(this).hide();
      return;
    }

    $(this).show();
    if (forceEnable)
      checkbox.attr('checked', true);
    var checked = checkbox.prop('checked');
    $('*[name=' + toggleName + '] input').prop('disabled', !checked);
    if (checked)
      $('*[name=' + toggleName + ']').addClass('in');
    else
      $('*[name=' + toggleName + ']').removeClass('in');
  });
}

function deserializeQueryString(url) {
  var queryString = url.substring( url.indexOf('?') + 1 );

  var obj = {};
  var split = queryString.split('&')
  for (var i = 0; i < split.length; i++) {
    var kv = split[i].split('=');
    var k = kv[0]
    var v = decodeURIComponent(kv[1] ? kv[1].replace(/\+/g, ' ') : kv[1]);
    if (v !== "")
      obj[k] = v;
  }
  return obj;
}

function toggleProcessing(suc) {
  runButton.toggleClass("running");
  if (runButton.hasClass("running")) {
    runButton.empty().append('<i class="fa fa-refresh fa-spin"></i>');
  } else {
    runButton.empty().append("Go!");
  }
  if (! suc)
    return;
  inputUrl.parent().removeClass('has-error').removeClass('has-success').addClass('has-' + suc);
}

function onClickImport(e) {
  console.log("enter#onClickImport");
  var qs = deserializeQueryString($("#import-feed input").val());
  for (k in qs) {
    var v = qs[k];
    $("*[name='" + k + "']").val(v);
    // $("*[name='" + k + "']").attr('disabled', false);
    inputUrl.val(qs.url);
  }
}

function onClickRun(e) {
  console.log("enter#onClickRun");
  var serializedForm = $("form :input").filter(function(index, element) {
    return $(element).val() != "";
  }).serialize();
  toggleProcessing();

  resultList.empty();
  resultCounter.html("0");

  var uri = inputUrl.val();
  var apiUri = 'api.php?url=' + encodeURIComponent(uri) + '&' + serializedForm;

  $("a#api-uri").attr('href', apiUri);

  $.get(apiUri, function(data) {
    // console.log(data);
    resultCounter.html($('item', data).size());
    var itemList = $('item', data);
    for (var i = 0; i < itemList.length; i++) {
      var item = itemList.get(i);
      var itemDiv = $('<div class="panel panel-default"/>');
      var itemLink = $("link", item).text();
      var itemDesc = $("<p>").append(
          $('description', item).text().indexOf('CDATA') > -1
          ? $('description', item).text()
          : $('description', item).html());
      itemDiv.append($("<div class='panel-heading'>")
        .append(i + ". ").append($('title', item).html()));
      itemDiv.append($("<div class='panel-body'>")
        .append("<b>Link: </b>").append($("<a>").append(itemLink).attr('href', itemLink))
        .append($("<br>"))
        .append("<b>Date: </b>").append($('pubDate', item).html())
        .append($("<br>"))
        .append("<b>Author: </b>").append($("<span>").append($('author', item).html()))
        .append($("<br>"))
        .append("<b>Description: </b>").append(itemDesc));
      resultList.append(itemDiv);
    };
    toggleProcessing('success');
  }).error(function(x) {
    toggleProcessing('error');
    alert(x.responseText);
  });
}

function loadExample() {
  console.log("enter#loadExample");
  $("#import-feed input").val($(this).attr('data-example-url'))
  onClickImport();
  syncFormEnable(true);
}


/*
 * Click handlers
 */
$("input").on('click', syncFormEnable);
$("select").on('change', syncFormEnable);
runButton.on('click', onClickRun);
$("#import-feed button").on('click', onClickImport);

$.ajax({
    url: 'doc/examples.json',
    type: 'GET',
    dataType: 'json',
    error: function(xhr, status, err) {
        console.log(status, xhr);
    },
    success: function(examples) {
        var $examplesMenu = $("#examples-menu");
        var categories = Object.keys(examples);
        for (var i = 0; i < categories.length; i++) {
            $examplesMenu.
                append($('<li class="divider">')).
                append($('<li class="dropdown-header">').append(categories[i]));
            var titles = Object.keys(examples[categories[i]]);
            for (var j = 0; j < titles.length; j++) {
                $examplesMenu.append(
                    '<li><a data-example-url=' +
                    examples[categories[i]][titles[j]] +
                    '>' + titles[j] + '</a></li>');
            }
        }
        $("a[data-example-url]").on('click', loadExample);
    },
});

/*
 * Run once at load
 */
syncFormEnable();
