<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('production.checklist.template.items.store', $template) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Add Item to {{ $template->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="item_name" class="form-label">Item Name</label>
                        <input type="text" name="name" id="item_name" class="form-control" placeholder="e.g. Check power distribution" required>
                    </div>

                    <div class="mb-3">
                        <label for="item_remarks" class="form-label">Remarks (Optional)</label>
                        <textarea name="remarks" id="item_remarks" class="form-control" rows="2" placeholder="Extra instructions..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="item_sequence" class="form-label">Sequence (Position)</label>
                        <input type="number" name="sequence" id="item_sequence" class="form-control" value="{{ ($template->items->max('sequence') ?? 0) + 1 }}">
                        <div class="form-text">The order in which the item appears.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>