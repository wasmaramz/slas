@extends ('errors.layout')

@section ('ERROR_CODE', '404')

@section ('ERROR_MESSAGE', ($exception->getMessage() != "") ? $exception->getMessage() : "The page you were looking for could not be found.")
