$(document).ready(function () {
  $("#searchForm").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const resultsDiv = $("#searchResults");

    $.ajax({
      url: "index.php?action=paper-search",
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (data) {
        resultsDiv.html(data);
      },
      error: function (xhr, status, error) {
        console.error("Search error:", error);
        resultsDiv.html(
          '<p class="text-danger">An error occurred while searching.</p>'
        );
      },
    });
  });
});
