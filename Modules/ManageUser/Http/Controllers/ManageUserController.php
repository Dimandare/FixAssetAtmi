<?php

namespace Modules\ManageUser\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Userdetail;
use App\Models\User;
use Illuminate\Support\Str; // Import Str class
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail; // Import your mailable class

class ManageUserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     
     */
    protected $menu = 'fixasetuser';
    protected $menu2 = 'nonfixasetuser';
    protected $menu3 = 'notifprofile';


    public function index(Request $request)
    {
// Ambil parameter 'type' dari query string

                $type = $request->input('type');

                // Filter pengguna berdasarkan tipe
                if ($type == 'fixaset') {
                    // Mengambil pengguna dengan role fixaset, misalnya role_id 14
                    $users = User::whereIn('role_id', [5,14,15,16,17,18,19])->get();
                    $navmenu = $this->menu;
                } else {
                    // Mengambil pengguna non-fixaset, role selain 14
                    $users = User::WhereNotIn('role_id', [14, 15, 16, 17, 18, 19])->get();
                    $navmenu = $this->menu2;

                    
                }
                

                // Kirim data pengguna ke view
                return view('manageuser::index', compact('users'))->with(['menu' => $navmenu ]);

}

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function lupapassword()
    {
        return view('manageuser::forgot_password'); // Optionally, you can just use the modal in your existing view.
    }

    // Handle the password reset link request
    public function sendResetLinkEmail(Request $request)
{
    // Validasi email input
    $request->validate([
        'email' => 'required|email',
    ]);

    // Cek apakah email ada di database
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        // Jika tidak ada, kembalikan error
        return back()->withErrors(['email' => 'Email tidak ditemukan.']);
    }

    // Generate a random token
    $token = mt_rand(1000, 9999);
    
    // Simpan token ke kolom remember_token
    $user->remember_token = $token;
    $user->save();

    // Kirim email reset password dengan token
    Mail::to($user->email)->send(new ResetPasswordMail($user, $token));

    // Kembalikan dengan pesan sukses
    return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
}
    
public function reset(Request $request)
{
    // Validate the request
    $request->validate([
        'password' => 'required|confirmed|min:8',
        'token' => 'required'
    ]);

    // Find the user associated with the token
    $userdata = User::where('remember_token', $request->token)->first();

    // If user not found, return an error
    if (!$userdata) {
        return back()->withErrors(['token' => __('Invalid token or user not found.')]);
    }

    // Update the user's password
    $userdata->password = bcrypt($request->password);
    $userdata->remember_token = null; // Clear the remember_token
    $userdata->save();

    // Redirect with success message
    return redirect()->route('login')->with('status', __('Password has been reset!'));
}




    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'username' => 'required|string|max:255', // Add validation for username
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role_id' => 'required|exists:roles,id',
        'divisi_id' => 'required|exists:divisis,id_divisi', // Ensure this matches your database
    ]);

    // Create a new user
    $user = User::create([
        'username' => $request->username, // Add username to the user creation
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role_id' => $request->role_id,
        'id_divisi' => $request->divisi_id // Add divisi_id here
    ]);

    // Optionally assign roles if using Spatie
    $user->assignRole($request->role_id);

    // Redirect or return a response
    return redirect()->route('manage-user.index')->with('success', 'User created successfully.');
}



    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function userdetails($id)
    {
        // Assuming userdetails is the model name, typically in PascalCase
        $userDetail = Userdetail::find($id);
    
        // Check if the user was found
        if (!$userDetail) {
            return redirect()->back()->with('error', 'User not found');
        }
    
        return view('manageuser::userdetails', [
            'menu' => $this->menu,
            'userDetail' => $userDetail // Pass user details to the view
        ]);
    }
    

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edituserdetails(Request $request, $id)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_induk_karyawan' => 'required|string|max:50',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Find the user by ID
        $userDetail = Userdetail::findOrFail($id);
    
        // Handle file upload for the photo
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            // Generate a unique file name with timestamp
            $fileName = time() . '_' . $file->getClientOriginalName();
    
            // Move the file to the public/uploads/photos directory
            $file->move(public_path('uploads/photos'), $fileName);
    
            // Save the file path to the database (relative path)
            $userDetail->foto = 'uploads/photos/' . $fileName; 
        }
    
        // Update other user details
        $userDetail->nama_lengkap = $validatedData['nama_lengkap'];
        $userDetail->jenis_kelamin = $validatedData['jenis_kelamin'];
        $userDetail->no_induk_karyawan = $validatedData['no_induk_karyawan'];
    
        // Save the changes
        $userDetail->save();
    
        // Redirect back with a success message
        return redirect()->route('manage-user.userdetails', $id)->with('success', 'User details updated successfully.');
    }
    
    
    

    

    

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function notifprofil()
    {
        return view('manageuser::profilnotif')->with(['menu' => $this->menu3 ]);

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}