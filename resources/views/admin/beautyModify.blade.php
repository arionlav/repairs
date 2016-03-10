<h2>Редактируем группу <span><span>#</span>{{ $beautyGroup[0]->group_beauty }}</span></h2>
<div class="lineDark"></div>
<div class="contentAdmin form">
    <form action="{{ url('admin/beauty-modify') }}" method="post">
        {!! csrf_field() !!}
        <div class="beautyTable">
            <table>
                <thead>
                <tr>
                    <th>Header</th>
                    <th>Description</th>
                    <th>Post</th>
                    <th>Num</th>
                </tr>
                </thead>
                <tbody>
                @foreach($beautyGroup as $beauty)
                    <input type="hidden" value="{{ $beauty->id }}" name="{{ $beauty->id }}">
                    <tr>
                        <td id="first">
                            <div id="textField">
                                <input type="text" class="beautyModify"
                                       name="header{{ $beauty->id }}" id="header{{ $beauty->id }}"
                                       value="{{ $beauty->header }}">
                            </div>
                        </td>
                        <td id="second">
                            <div id="textField">
                                <input type="text" class="beautyModify"
                                       name="description{{ $beauty->id }}" id="description{{ $beauty->id }}"
                                       value="{{ $beauty->description }}">
                            </div>
                        <td id="third">
                            <select name="post{{ $beauty->id }}" id="post{{ $beauty->id }}"
                                    class="inTable beautyModify">
                                @foreach($posts as $post)
                                <option value="{{ $post->id }}"{{
                                    ($post->id == $beauty->id_post) ? ' selected' : '' }}>
                                    {{ $post->id }} - {{ $post->header }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td id="forth">
                            <div id="textField">
                                <input type="text" style="width: 50px;" class="beautyModifyNumber"
                                       name="number{{ $beauty->id }}" id="number{{ $beauty->id }}"
                                       value="{{ $beauty->number }}">
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <button type="submit" id="button" class="leanModalButton">
            <div class="real_button">
                <img src="{{ config('var.pathToRoot') }}/resources/img/real_button_admin.png"/>
                <p class="registrationButtonText">ВЫПОЛНИТЬ</p>
                <div class="overlayHoverButtonRed"></div>
            </div>
        </button>
    </form>
</div>
