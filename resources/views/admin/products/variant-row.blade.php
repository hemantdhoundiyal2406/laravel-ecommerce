<div class="row g-2 align-items-end">
    <div class="col-6 col-md-2"><label class="form-label small">Size</label><input name="variant_sizes[]" class="form-control" value="{{ $variant?->size }}"></div>
    <div class="col-6 col-md-3"><label class="form-label small">Color</label><input name="variant_colors[]" class="form-control" value="{{ $variant?->color }}"></div>
    <div class="col-6 col-md-2"><label class="form-label small">Color code</label><input name="variant_color_codes[]" class="form-control" value="{{ $variant?->color_code }}" placeholder="#111827"></div>
    <div class="col-6 col-md-2"><label class="form-label small">Price +/-</label><input type="number" step="0.01" name="variant_adjustments[]" class="form-control" value="{{ $variant?->price_adjustment ?? 0 }}"></div>
    <div class="col-6 col-md-2"><label class="form-label small">Stock</label><input type="number" name="variant_stocks[]" class="form-control" value="{{ $variant?->stock_quantity ?? 0 }}"></div>
</div>
