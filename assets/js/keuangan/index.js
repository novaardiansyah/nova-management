function toggleTabs(event, section)
{
  event.preventDefault();
  let listTabs = document.querySelectorAll('.nav-link');
  
  listTabs.forEach((element) => {
    element.classList.remove('active');
  });

  event.target.classList.add('active');

  let tabContents = document.querySelectorAll('.section.tab-content');
  tabContents.forEach((element) => {
    element.classList.add('d-none');
  });
  
  let tabContent = document.querySelector(`.section.tab-content.${section}`);
  tabContent.classList.remove('d-none');

  switch (section) {
    case 'accountList':
      accountList();
      break;
    case 'typeAccountList':
      typeAccountList();
      break;
    case 'typeCurrencyList':
      typeCurrencyList();
      break;
    default:
      break;
  }

  return false;
}