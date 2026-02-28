@props([
    'modalId',
    'title' => 'Update status',
    'action',
    'statusOptions' => [],
    'hiddenFields' => [],
])

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ $action }}" data-confirm data-confirm-message="Confirm status update?">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @foreach ($hiddenFields as $name => $value)
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                    @endforeach

                    <div class="mb-3">
                        <x-form.select name="targetStatus" label="New status" :options="$statusOptions" required />
                    </div>

                    <x-form.textarea name="reason" label="Reason" rows="3" placeholder="Reason for change..." />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
