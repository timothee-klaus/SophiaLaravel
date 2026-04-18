<x-mail::message>
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #1e3a8a; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">INSTITUT SCOLAIRE SOPHIA</h1>
    <p style="color: #64748b; font-size: 14px; margin-top: 5px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Le Don de Dieu</p>
</div>

# Validation de votre inscription

Bonjour **{{ $name }}**,

Merci de votre intérêt pour rejoindre l'équipe de l'Institut Scolaire Sophia. Pour valider votre adresse email et soumettre votre demande d'accès au directeur, veuillez utiliser le code de vérification suivant :

<div style="background-color: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 16px; padding: 40px 20px; margin: 30px 0; text-align: center;">
    <span style="font-family: 'Courier New', Courier, monospace; font-size: 56px; font-weight: 900; color: #1e3a8a; letter-spacing: 15px; display: block; line-height: 1;">{{ $code }}</span>
    <p style="color: #94a3b8; font-size: 12px; margin-top: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Code de sécurité à usage unique</p>
</div>

Si vous n'avez pas initié cette demande, vous pouvez ignorer ce message en toute sécurité.

Cordialement,<br>
**L'équipe {{ config('app.name') }}**
</x-mail::message>
