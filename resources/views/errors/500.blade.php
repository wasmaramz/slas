@extends ('errors.layout')

@section ('ERROR_CODE', '500')

@section ('ERROR_MESSAGE', ($exception->getMessage() != "") ? $exception->getMessage() : "Whoopps, something went wrong.")
