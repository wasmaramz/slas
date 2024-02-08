@extends ('errors.layout')

@section ('ERROR_CODE', '403')

@section ('ERROR_MESSAGE', ($exception->getMessage() != "") ? $exception->getMessage() : "You do not have access to this page.")
