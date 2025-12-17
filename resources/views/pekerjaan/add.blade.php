@extends('base')
@section('title','Pekerjaan')
@section('menupekerjaan', 'underline decoration-4 underline-offset-7')
@section('content')
    <section class="p-4 bg-white rounded-lg min-h-[50vh]">
        <h1 class="text-3xl font-bold text-[#C0392B] mb-6 text-center">Pekerjaan</h1>
        <div class="mx-auto max-w-screen-xl">
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-300" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('pekerjaan.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" autocomplete="off" required>{{ old('deskripsi') }}</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Captcha</label>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="flex-shrink-0">
                            <img id="captcha-img" src="{{ route('captcha') }}" alt="captcha" class="border border-gray-300 rounded">
                        </div>
                        <button type="button" onclick="document.getElementById('captcha-img').src='{{ route('captcha') }}?'+Math.random()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            🔄 Refresh Captcha
                        </button>
                    </div>
                    <input type="text" name="captcha" placeholder="Masukkan kode captcha" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required autocomplete="off">
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="reset" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer">Reset</button>
                    <button type="submit" class="rounded-md bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700 cursor-pointer">Simpan</button>
                </div>
            </form>
        </div>
    </section>
@endsection
