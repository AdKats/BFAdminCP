@extends('layout.main')

@section('content')
    {!! Former::open()->route('admin.site.users.update', [$user->id])->rules([
        'username' => 'required|alpha_num|min:4',
        'email'    => 'required|email',
        'role'     => 'required'
    ]) !!}

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{ trans('site.admin.users.edit.details') }}</h3>
                </div>

                <div class="box-body">
                    {!! Former::text('username')->label(trans('site.admin.users.edit.inputs.username.label')) !!}
                    {!! Former::email('email')->label(trans('site.admin.users.edit.inputs.email.label')) !!}
                    {!! Former::select('role')->options($roles, $user->roles[0]->id)->label(trans('site.admin.users.edit.inputs.role.label')) !!}

                    <div class="form-block">
                        <div class="form-inline">
                            <label for="account_status" class="col-sm-2 control-label">{{ trans('site.admin.users.edit.inputs.account_status.label') }}</label>

                            <div class="col-sm-10">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="account_status" value="1" @if($user->confirmed) checked @endif>
                                        Active
                                    </label>
                                    &nbsp;
                                    <label>
                                        <input type="radio" name="account_status" value="0" @if(! $user->confirmed) checked @endif>
                                        Inactive
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Former::select('language')->label(trans('site.admin.users.edit.inputs.lang.label'))->options(Config::get('bfacp.site.languages'))->value($user->setting->lang) !!}

                    <div class="form-group">
                        <label for="generate_pass" class="col-sm-2 control-label">&nbsp;</label>

                        <div class="col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="generate_pass" value="1">
                                    {{ trans('site.admin.users.edit.inputs.genpass.label') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-sm-offset-4 col-lg-10 col-sm-8">
                            <button type="submit" class="btn bg-green">
                                <i class="fa fa-floppy-o"></i>&nbsp;<span>{{ trans('site.admin.users.edit.buttons.save') }}</span>
                            </button>
                            {!! link_to_route('admin.site.users.index', trans('site.admin.users.edit.buttons.cancel'), [], ['class' => 'btn bg-blue', 'target' => '_self']) !!}
                            <button class="btn bg-red" id="delete-user">
                                <i class="fa fa-trash"></i>&nbsp;<span>{{ trans('site.admin.users.edit.buttons.delete') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-6">
            @include('partials.player._soldiers', ['user' => $user])
        </div>
    </div>
    {!! Former::close() !!}
@stop

@section('scripts')
    <script type="text/javascript">
        $('#delete-user').click(function (e) {
            e.preventDefault();

            var btn = $(this);

            var csrf = $("input[name='_token']").val();

            if (confirm('Are you sure you want to delete {{ $user->username }}? This can\'t be undone.')) {
                btn.find('i').removeClass('fa-trash').addClass('fa-spinner fa-pulse');
                btn.parent().find('button').attr('disabled', true);
                $.ajax({
                    url: "{{ route('admin.site.users.destroy', $user->id) }}",
                    type: 'DELETE',
                    data: {
                        _token: csrf
                    }
                })
                .done(function (data) {
                    toastr.success(data.data.messages[0]);
                    setTimeout(function() {
                        window.location.href = data.data.url;
                    }, 2000);
                })
                .fail(function () {
                    console.log("error");
                    toastr.error('Sorry, an error occurred and your request may not have gone through.');
                })
                .always(function () {
                    btn.find('i').removeClass('fa-spinner fa-pulse').addClass('fa-trash');
                    btn.parent().find('button').attr('disabled', false);
                });
            }
        });
    </script>
@stop