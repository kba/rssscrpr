var resultList = $("div#result-list");
var resultCounter = $("#results-found")
var inputUrl = $("#input-group-url input");
var runButton = $("#input-group-url button");

function syncFormEnable() {
  $('input[type=checkbox][data-enable]').each(function() {
    var checked = $(this).prop('checked');
    var toggleName = $(this).attr('data-enable');
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
  var qs = deserializeQueryString($("#import-feed input").val());
  for (k in qs) {
    var v = qs[k];
    $("*[name='" + k + "']").val(v);
    inputUrl.val(qs.url);
  }
}

function onClickRun(e) {
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
      itemDiv.append($("<div class='panel-heading'>")
        .append(i++ + ". ").append($('title', item).html()));
      itemDiv.append($("<div class='panel-body'>")
        .append("<b>Link: </b>").append($("<a>").append(itemLink).attr('href', itemLink))
        .append($("<br>"))
        .append("<b>Date: </b>").append($('pubDate', item).html())
        .append($("<br>"))
        .append("<b>Author: </b>").append($("<span>").append($('author', item).html()))
        .append($("<br>"))
        .append("<b>Description: </b>").append($("<p>").append($('description', item).text())));
      resultList.append(itemDiv);
    };
    toggleProcessing('success');
  }).error(function(x) {
    toggleProcessing('error');
    alert(x.responseText);
  });
}

function loadExample() {
  $("#import-feed input").val($(this).attr('data-example-url'))
  onClickImport();
}


/*
 * Click handlers
 */
$("input").on('click', syncFormEnable);

runButton.on('click', onClickRun);

$("a[data-example-url]").on('click', loadExample);

$("#import-feed button").on('click', onClickImport);

/*
 * Run once at load
 */
syncFormEnable();
