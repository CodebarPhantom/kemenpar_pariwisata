  <div class="modal fade" id="modal-confirmation" tabindex="-1" role="dialog" aria-labelledby="modal-confirmation-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form method="POST" role="form" id="modal-action">
          @method('POST')
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="modal-confirmation-title">Confirmation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p id="modal-message"></p>
            <input type="hidden" id="input-value" name="confirmation_id" value="">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Tidak') }}</button>
              <button type="submit" class="btn btn-success">{{ __('Ya') }}</button>
          </div>      
      </form>
      </div>
    </div>
  </div>


@push('js')
<script>
  $('#modal-confirmation').on('show.bs.modal', function(event){
      var text = $(event.relatedTarget).data('text');
      var inputValue = $(event.relatedTarget).data('value');

      $('#modal-action').attr('action', $(event.relatedTarget).data('href'));
      $('#modal-message').text(text+" ?");
      $('#input-value').val(inputValue);

  });
</script>
@endpush
  
