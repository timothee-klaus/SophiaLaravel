<x-mail::message>
# Validation de votre inscription

Bonjour {{ $name }},

Merci de votre intérêt pour rejoindre l'équipe de l'Institut Scolaire Sophia. Pour valider votre adresse email et soumettre votre demande d'accès au directeur, veuillez utiliser le code de vérification suivant :

<x-mail::panel>
# {{ $code }}
</x-mail::panel>

Si vous n'avez pas initié cette demande, vous pouvez ignorer ce message.

Cordialement,<br>
L'équipe {{ config('app.name') }}
</x-mail::message>
