{{--
<div>
    <canvas id="signature-canvas-{{ $getId() }}" style="border:1px solid #ccc; width:100%; height:150px;"></canvas>
    <button type="button" onclick="clearSignature{{ $getId() }}()">Clear</button>
    <textarea id="signature-input-{{ $getId() }}" name="{{ $getName() }}" style="display:none;">{{ $getState() }}</textarea>
</div>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
<script>
    const canvas{{ $getId() }} = document.getElementById('signature-canvas-{{ $getId() }}');
    const input{{ $getId() }} = document.getElementById('signature-input-{{ $getId() }}');
    const signaturePad{{ $getId() }} = new SignaturePad(canvas{{ $getId() }});
    function clearSignature{{ $getId() }}() {
        signaturePad{{ $getId() }}.clear();
        input{{ $getId() }}.value = '';
    }
    canvas{{ $getId() }}.addEventListener('mouseup', function () {
        input{{ $getId() }}.value = signaturePad{{ $getId() }}.toDataURL();
        @this.set('{{ $getStatePath() }}', input{{ $getId() }}.value);
    });
</script> --}}
