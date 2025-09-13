{{--
    Vista de los ítems de la lista de contactos, búsqueda y mensajes guardados.
    Cada bloque muestra el avatar, nombre, último mensaje y contador de mensajes no leídos.
    El atributo data-imagen permite que el JS fuerce la imagen correcta.
--}}
{{-- -------------------- Saved Messages -------------------- --}}
@if ($get == 'saved')
    <table class="messenger-list-item" data-contact="{{ Auth::user()->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
                <div class="saved-messages avatar av-m">
                    <span class="far fa-bookmark"></span>
                </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ Auth::user()->id }}" data-type="user">Mensajes Guardados <span>Tú</span></p>
                <span>Guardar mensajes en secreto</span>
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- Contact list -------------------- --}}
@if ($get == 'users' && !!$lastMessage)
    <?php
    $lastMessageBody = mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8');
    // Reducir a 20 caracteres para que se vea mejor en la vista previa
    $lastMessageBody = strlen($lastMessageBody) > 20 ? mb_substr($lastMessageBody, 0, 20, 'UTF-8') . '...' : $lastMessageBody;
    ?>
    <table class="messenger-list-item" data-contact="{{ $user->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td style="position: relative">
                {{-- NO mostrar estado activo inicial - se manejará 100% via JavaScript/Pusher --}}
                <div class="avatar av-m"
                    style="background-image: url('{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/img.jpg') }}');"
                    data-imagen="{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/img.jpg') }}">
                </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ $user->id }}" data-type="user">
                    {{ strlen($user->name) > 12 ? trim(substr($user->name, 0, 12)) . '..' : $user->name }}
                    <span class="contact-item-time"
                        data-time="{{ $lastMessage->created_at }}">{{ $lastMessage->timeAgo }}</span>
                </p>
                <span>
                    {{-- Last Message user indicator --}}
                    {!! $lastMessage->from_id == Auth::user()->id ? '<span class="lastMessageIndicator">You :</span>' : '' !!}
                    {{-- Last message body --}}
                    @if ($lastMessage->attachment == null)
                        {!! $lastMessageBody !!}
                    @else
                        <span class="fas fa-file"></span> Attachment
                    @endif
                </span>
                {{-- New messages counter --}}
                {!! $unseenCounter > 0 ? '<b>' . $unseenCounter . '</b>' : '' !!}
            </td>
        </tr>
    </table>
@endif

{{-- -------------------- Search Item -------------------- --}}
@if ($get == 'search_item')
    <table class="messenger-list-item" data-contact="{{ $user->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
                <div class="avatar av-m"
                    style="background-image: url('{{ $user->imagen ? asset('perfiles/' . $user->imagen) : asset('img/img.jpg') }}');">
                </div>
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ $user->id }}" data-type="user">
                    {{ strlen($user->name) > 12 ? trim(substr($user->name, 0, 12)) . '..' : $user->name }}
            </td>

        </tr>
    </table>
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if ($get == 'sharedPhoto')
    <div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif
