function adaptPageDropdown(dataTable) {
  const selector = dataTable.wrapper.querySelector(".dataTable-selector")
  selector.parentNode.parentNode.insertBefore(selector, selector.parentNode)
  selector.classList.add("form-select")
}

function adaptPagination(dataTable) {
  const paginations = dataTable.wrapper.querySelectorAll(
    "ul.dataTable-pagination-list"
  )

  for (const pagination of paginations) {
    pagination.classList.add(...["pagination", "pagination-primary"])
  }

  const paginationLis = dataTable.wrapper.querySelectorAll(
    "ul.dataTable-pagination-list li"
  )

  for (const paginationLi of paginationLis) {
    paginationLi.classList.add("page-item")
  }

  const paginationLinks = dataTable.wrapper.querySelectorAll(
    "ul.dataTable-pagination-list li a"
  )

  for (const paginationLink of paginationLinks) {
    paginationLink.classList.add("page-link")
  }
}

function initDataTables(idTable)
{
  const table       = document.getElementById(idTable);
  const loaderTable = document.getElementById('loader-table');

  let dataTable = new simpleDatatables.DataTable(table);
  dataTable.on("datatable.page", adaptPagination);
  
  adaptPageDropdown(dataTable);
  adaptPagination(dataTable);

  loaderTable.style.visibility = 'hidden';
}