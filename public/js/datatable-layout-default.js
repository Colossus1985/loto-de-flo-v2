window.datatableLayout = {
  topStart: 'info',
  topEnd: 'paging',

  top1Start: {
      pageLength: {},
      buttons: [
          { extend: 'copyHtml5', text: 'Copier', className:' btn btn-sm btn-light border border-primary' },
          { extend: 'excelHtml5', text: 'Excel', className:' btn btn-sm btn-light border border-primary' },
          { extend: 'pdfHtml5', text: 'PDF', className:' btn btn-sm btn-light border border-primary' },
          { extend: 'print', text: 'Imprimer', className:' btn btn-sm btn-light border border-primary' }
      ],
  },
  top1End: {
      search: {
          placeholder: 'Chercher',
          text: ''
      }
  },

  bottomStart: 'info',
  bottomEnd: 'paging',
}

window.datatableLangue = {
  url: '/js/datatable_fr.json',
  select: {rows: "", columns: "",cells: ""}
}