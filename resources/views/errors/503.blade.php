@extends ('errors.layout')

@section ('ERROR_CODE', '503')

@section ('ERROR_MESSAGE', ($exception->getMessage() != "") ? $exception->getMessage() : "Be right back.")
