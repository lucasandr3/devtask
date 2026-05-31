<div>
    <x-input-label for="name" value="Nome" />
    <x-text-input type="text" name="name" id="name" value="{{ old('name', $client->name ?? '') }}" required class="mt-1" />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <x-input-label for="document" value="CPF/CNPJ" />
        <x-text-input type="text" name="document" id="document" value="{{ old('document', $client->document ?? '') }}" class="mt-1" data-mask="cpf-cnpj" placeholder="000.000.000-00 ou 00.000.000/0000-00" />
        <x-input-error :messages="$errors->get('document')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="phone" value="Telefone" />
        <x-text-input type="text" name="phone" id="phone" value="{{ old('phone', $client->phone ?? '') }}" class="mt-1" data-mask="phone" placeholder="(00) 00000-0000" />
    </div>
</div>
<div>
    <x-input-label for="email" value="E-mail" />
    <x-text-input type="email" name="email" id="email" value="{{ old('email', $client->email ?? '') }}" class="mt-1" />
</div>
<div>
    <x-input-label for="notes" value="Observações" />
    <textarea name="notes" id="notes" rows="3" class="input mt-1">{{ old('notes', $client->notes ?? '') }}</textarea>
</div>
