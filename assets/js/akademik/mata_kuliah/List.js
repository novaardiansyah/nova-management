$(document).ready(function () {
  $('#mata_kuliah_list').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url('akademik/mata_kuliah/data_list'),
      type: 'GET'
    },
    columns: [
      { data: 'no', orderable: false },
      { data: 'kode', orderable: false },
      { data: 'nama', orderable: false },
      { data: 'sks', orderable: false },
      { data: 'semester', orderable: false },
      { 
        data: 'deskripsi', 
        orderable: false,
        render: function (data, type, row) {
          return cut_word(data, 12);
        }
      },
      { 
        data: 'is_active',
        orderable: false,
        render: function(data, type, row) {
          if (data == '1') {
            return `<span class="badge badge-success">Active</span>`
          }
          
          return `<span class="badge badge-danger">Not-Active</span>`
        }
      },
      {
        data: 'id', 
        orderable: false, 
        searchable: false,
        render: function (data, type, row) {
          return `
            <div class="btn-group">
              <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Action
              </button>
              <div class="dropdown-menu">
                <button type="button" class="dropdown-item" onclick="return modalEdit(true, '${data}')">Edit</button>
                <button type="button" class="dropdown-item" onclick="return remove('${data}')">Delete</button>
              </div>
            </div>
          `;
        }
      },
    ]
  }); 

  $('.is-invalid').on('click focus input change blur', function () {
    $(this).removeClass('is-invalid');
    $(this).next('.invalid-feedback').remove();
  });
});


// * Feature: Store data (Start)
function modalAdd(show = true)
{
  let modal = $('#modalAdd');
  let modalBody = modal.find('.modal-body');

  if (show !== true) {
    modalBody.html('');
    return modal.modal('hide');
  }

  modalBody.html(`
    <form action="" method="post">
      <div class="form-group row">
        <label for="kode" class="col-md-3 col-form-label text-md-right">
          Kode Mata Kuliah <span class="text-danger">*</span>
        </label>
        <div class="col-md-8">
          <input type="text" class="form-control" id="kode" name="kode" />
        </div>
      </div>
      <!-- form-group -->

      <div class="form-group row">
        <label for="nama" class="col-md-3 col-form-label text-md-right">
          Nama <span class="text-danger">*</span>
        </label>
        <div class="col-md-8">
          <input type="text" class="form-control" id="nama" name="nama" />
        </div>
      </div>
      <!-- form-group -->

      <div class="form-group row">
        <label for="sks" class="col-md-3 col-form-label text-md-right">
          Jumlah SKS <span class="text-danger">*</span>
        </label>
        <div class="col-md-8">
          <input type="number" class="form-control prevent-scroll" id="sks" name="sks" onkeypress="return onlyNumber(event)" onkeyup="return max_length(event, 1)" />
        </div>
      </div>
      <!-- form-group -->

      <div class="form-group row">
        <label for="semester" class="col-md-3 col-form-label text-md-right">
          Berlaku Semester <span class="text-danger">*</span>
        </label>
        <div class="col-md-8">
          <input type="number" class="form-control prevent-scroll" id="semester" name="semester" onkeypress="return onlyNumber(event)" onkeyup="return max_length(event, 2)" />
        </div>
      </div>
      <!-- form-group -->

      <div class="form-group row">
        <label for="deskripsi" class="col-md-3 col-form-label text-md-right">
          Deskripsi
        </label>
        <div class="col-md-8">
          <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control"></textarea>
        </div>
      </div>
      <!-- form-group -->

      <div class="form-group row">
        <label for="is_active" class="col-md-3 col-form-label text-md-right">
          Status
        </label>
        <div class="col-md-8">
          <select name="is_active" id="is_active" class="form-control custom-select">
            <option value="1">Active</option>
            <option value="0">Not-Active</option>
          </select>
        </div>
      </div>
      <!-- form-group -->

      <div class="row">
        <div class="col-md-8 offset-md-3 text-md-right">
          <button type="button" class="btn btn-sm btn-secondary" onclick="return modalAdd(false)">
            <i class="fa fa-fw fa-times-circle"></i> Cancel
          </button>

          <button type="button" class="btn btn-sm btn-primary" onclick="return store(this.form)">
            <i class="fa fa-fw fa-save"></i> Save
          </button>
        </div>
      </div>
    </form>
  `);

  return modal.modal('show');
}

function store(form = '')
{
  let formData = new FormData(form);
  formData.append('csrf', config.csrf_token);

  $.ajax({
    url: base_url('akademik/mata-kuliah/store'), 
    type: 'POST',
    data: formData,
    dataType: 'json',
    contentType: false,
    processData: false,
    beforeSend: function () {
      $(form).find('button[type="button"]').attr('disabled', true);
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();
    },
    success: function (response) {
      config.csrf_token = response.csrf;
      
      if (response.status == false && response.form_errors != undefined) {
        let errors = response.form_errors;
        Object.keys(errors).forEach(function (key) {
          let error = errors[key];
          $(form).find(`#${key}`).addClass('is-invalid');
          $(form).find(`#${key}`).after(`<div class="invalid-feedback">${error}</div>`);
        });
      }

      if (response.status == true) {
        alerts({ text: response.message, type: 'success', timer: 3000, close: () => modalAdd(false) });
        return $('#mata_kuliah_list').DataTable().ajax.reload(null, false);
      }
    },
    error: function (xhr, status, error) {
      console.log({xhr, status, error});
    },
    complete: function () {
      $(form).find('button[type="button"]').attr('disabled', false);
    }
  });
}
// * Feature: Store data (End)


// * Feature: Update data (Start)
function modalEdit(show = true, id = '')
{
  let modal = $('#modalEdit');
  let modalBody = modal.find('.modal-body');

  if (show !== true) {
    modalBody.html('');
    return modal.modal('hide');
  }

  let data = get_mata_kuliah(id);
  if (data == null) return alerts({ text: 'Maaf data yang anda pilih tidak ditemukan.', type: 'error', timer: 3000 });

  modalBody.html(`
    <form action="" method="post">
      <input type="hidden" name="id" value="${data.id}" />

      <div class="form-group row">
        <label for="kode" class="col-md-3 col-form-label text-md-right">
          Kode Mata Kuliah <span class="text-danger">*</span>
        </label>
        <div class="col-md-8">
          <input type="text" class="form-control" id="kode" name="kode" value="${data.kode}" />
        </div>
      </div>
      <!-- form-group -->

      <div class="form-group row">
        <label for="nama" class="col-md-3 col-form-label text-md-right">
          Nama <span class="text-danger">*</span>
        </label>
        <div class="col-md-8">
          <input type="text" class="form-control" id="nama" name="nama" value="${data.nama}" />
        </div>
      </div>
      <!-- form-group -->

      <div class="form-group row">
        <label for="sks" class="col-md-3 col-form-label text-md-right">
          Jumlah SKS <span class="text-danger">*</span>
        </label>
        <div class="col-md-8">
          <input type="number" class="form-control prevent-scroll" id="sks" name="sks" onkeypress="return onlyNumber(event)" onkeyup="return max_length(event, 1)" value="${data.sks}" />
        </div>
      </div>
      <!-- form-group -->

      <div class="form-group row">
        <label for="semester" class="col-md-3 col-form-label text-md-right">
          Berlaku Semester <span class="text-danger">*</span>
        </label>
        <div class="col-md-8">
          <input type="number" class="form-control prevent-scroll" id="semester" name="semester" onkeypress="return onlyNumber(event)" onkeyup="return max_length(event, 2)" value="${data.semester}" />
        </div>
      </div>
      <!-- form-group -->

      <div class="form-group row">
        <label for="deskripsi" class="col-md-3 col-form-label text-md-right">
          Deskripsi
        </label>
        <div class="col-md-8">
          <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" value="${data.kode}">${data.deskripsi}</textarea>
        </div>
      </div>
      <!-- form-group -->

      <div class="form-group row">
        <label for="is_active" class="col-md-3 col-form-label text-md-right">
          Status
        </label>
        <div class="col-md-8">
          <select name="is_active" id="is_active" class="form-control custom-select" value="${data.is_active}">
            <option value="1" ${data.is_active == 1 ? 'selected' : ''}>Active</option>
            <option value="0" ${data.is_active == 0 ? 'selected' : ''}>Not-Active</option>
          </select>
        </div>
      </div>
      <!-- form-group -->

      <div class="row">
        <div class="col-md-8 offset-md-3 text-md-right">
          <button type="button" class="btn btn-sm btn-secondary" onclick="return modalEdit(false)">
            <i class="fa fa-fw fa-times-circle"></i> Cancel
          </button>

          <button type="button" class="btn btn-sm btn-primary" onclick="return update(this.form)">
            <i class="fa fa-fw fa-save"></i> Update
          </button>
        </div>
      </div>
    </form>
  `);

  return modal.modal('show');
}

function update(form)
{
  let formData = new FormData(form);
  formData.append('csrf', config.csrf_token);

  $.ajax({
    url: base_url('akademik/mata-kuliah/update'), 
    type: 'POST',
    data: formData,
    dataType: 'json',
    contentType: false,
    processData: false,
    beforeSend: function () {
      $(form).find('button[type="button"]').attr('disabled', true);
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').remove();
    },
    success: function (response) {
      config.csrf_token = response.csrf;
      
      if (response.status == false && response.form_errors != undefined) {
        let errors = response.form_errors;
        Object.keys(errors).forEach(function (key) {
          let error = errors[key];
          $(form).find(`#${key}`).addClass('is-invalid');
          $(form).find(`#${key}`).after(`<div class="invalid-feedback">${error}</div>`);
        });
      }

      if (response.status == true) {
        alerts({ text: response.message, type: 'success', timer: 3000, close: () => modalEdit(false) });
        return $('#mata_kuliah_list').DataTable().ajax.reload(null, false);
      }
    },
    error: function (xhr, status, error) {
      console.log({xhr, status, error});
    },
    complete: function () {
      $(form).find('button[type="button"]').attr('disabled', false);
    }
  });
}
// * Feature: Update data (End)


function remove(id = '', direct = false)
{
  if (direct == false) {
    alerts({ needConfirm: true, text: 'Are you sure want to delete this data?', type: 'warning', confirmButtonText: 'Yes, delete it!', confirm: () => remove(id, true) })
  }

  if (direct == true) {
    $.ajax({
      url: base_url('akademik/mata-kuliah/delete'),
      type: 'POST',
      data: { id: id, csrf: config.csrf_token },
      dataType: 'json',
      beforeSend: function () {},
      success: function (response) {
        config.csrf_token = response.csrf;
  
        if (response.status == true) {
          alerts({ text: response.message, type: 'success', timer: 3000 });
          return $('#mata_kuliah_list').DataTable().ajax.reload(null, false);
        }

        if (response.status == false) {
          return alerts({ text: response.message, type: 'error', timer: 3000 });
        }
      },
      error: function (xhr, status, error) {
        console.log({xhr, status, error});
      },
      complete: function () {}
    });
  }
}

function get_mata_kuliah(id = null)
{
  let data = null;

  $.ajax({
    url: base_url('akademik/mata-kuliah/show'),
    type: 'POST',
    data: { id: id, csrf: config.csrf_token },
    dataType: 'json',
    async: false,
    beforeSend: function () {},
    success: function (response) {
      config.csrf_token = response.csrf;
      if (response.status == true) data = response.data;
    },
    error: function (xhr, status, error) {
      console.log({xhr, status, error});
    },
    complete: function () {}
  });

  return data;
}