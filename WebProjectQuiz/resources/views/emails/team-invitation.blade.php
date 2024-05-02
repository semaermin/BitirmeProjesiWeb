@component('mail::message')
{{ __(':team takımına katılmak için davet edildiniz!', ['team' => $invitation->team->name]) }}

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
{{ __('Hesabınız yoksa, aşağıdaki düğmeye tıklayarak bir tane oluşturabilirsiniz. Hesap oluşturduktan sonra, bu e-postadaki davet kabul düğmesine tıklayarak daveti kabul edebilirsiniz:') }}

@component('mail::button', ['url' => route('register')])
{{ __('Hesap Oluştur') }}
@endcomponent

{{ __('Zaten bir hesabınız varsa, daveti aşağıdaki düğmeye tıklayarak kabul edebilirsiniz:') }}

@else
{{ __('Bu daveti aşağıdaki düğmeye tıklayarak kabul edebilirsiniz:') }}
@endif

@component('mail::button', ['url' => $acceptUrl])
{{ __('Daveti Kabul Et') }}
@endcomponent

{{ __('Bu takıma bir davet almayı beklemediyseniz, bu e-postayı yok sayabilirsiniz.') }}
@endcomponent
