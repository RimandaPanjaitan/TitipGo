<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = ['provider', 'account_number', 'account_holder', 'is_primary'];
}