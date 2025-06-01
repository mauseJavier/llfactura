<div>
    <div class="container">
        <h3>Galeria de Imágenes</h3>
        <article>

            <ul>
                @forelse($imagenes as $img)
        
                    <li style="display: inline-block; margin: 16px; vertical-align: top;">
                        <a href="https://res.cloudinary.com/{{ env('CLOUDINARY_CLOUD_NAME') }}/image/upload/{{ $img }}" target="_blank" style="display: block; text-align: center;">
                            <img src="https://res.cloudinary.com/{{ env('CLOUDINARY_CLOUD_NAME') }}/image/upload/{{ $img }}" alt="{{ $img }}" style="max-width:200px; max-height:200px; box-shadow: 0 4px 16px rgba(0,0,0,0.2); border-radius: 8px; background: #fff;">
                        </a>
                    </li>
                @empty
                    <li>No hay imágenes en Cloudinary.</li>
                @endforelse
            </ul>

        </article>
    </div>
</div>
