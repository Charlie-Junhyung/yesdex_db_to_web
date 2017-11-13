function downloadCSV (csv, filename) {
  var csvFile
  var downloadLink

  csv = '\ufeff' + csv
  csvFile = new Blob([csv], {type: 'type: "text/csv;charset=UTF-8"'}) // ready CSV file

  downloadLink = document.createElement('a') // Download link

  downloadLink.download = filename
  downloadLink.href = window.URL.createObjectURL(csvFile) // Create a link to the file
  downloadLink.style.display = 'none' // Hide download link

  document.body.appendChild(downloadLink) // Add the link to DOM
  downloadLink.click() // Click download link
}

function exportTableToCSV (filename) {
  var sel = document.getElementById('select_date')
  filename = 'statistics_' + sel.value + '.csv'
  var csv = []
  var rows = document.querySelectorAll('table tr')

  for (var i = 0; i < rows.length; i++) {
    var row = []
    var cols = rows[i].querySelectorAll('td, th')
    for (var j = 0; j < cols.length; j++) {
      row.push(cols[j].innerText)
    }
    csv.push(row.join(','))
  }

  // Download CSV file
  downloadCSV(csv.join('\n'), filename)
}
