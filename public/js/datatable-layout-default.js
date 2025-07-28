window.datatableDom = `
  <'row mb-2'
    <'col-md-6'i>
    <'col-md-6 text-end'p>
  >
  <'row mb-2'
    <'col-md-6 d-flex align-items-center'l>
    <'col-md-6 text-end'f>
  >
  <'row mb-2'
    <'col-md-12'B>
  >
  <'row'
    <'col-12'tr>
  >
  <'row mt-2'
    <'col-md-6'i>
    <'col-md-6 text-end'p>
  >
`;


window.datatableButtons = [
  {
    extend: 'copyHtml5',
    text: 'Copier',
    className: 'btn btn-sm btn-light border border-primary'
  },
  {
    extend: 'excelHtml5',
    text: 'Excel',
    className: 'btn btn-sm btn-light border border-primary'
  },
  {
    extend: 'pdfHtml5',
    text: 'PDF',
    className: 'btn btn-sm btn-light border border-primary'
  },
  {
    extend: 'print',
    text: 'Imprimer',
    className: 'btn btn-sm btn-light border border-primary'
  }
];

window.datatableLangue = {
  url: '/js/datatable_fr.json',
  select: { rows: "", columns: "", cells: "" }
};
